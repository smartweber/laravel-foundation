<?php namespace App\Console\Commands;

use App\Models\Crt;
use Doctrine\ORM\Query\ResultSetMapping;
use App\Models\UserMFP;
use App\Models\Dfp;
use App\Models\RestaurantMFP;
use App\Models\Restaurants;
use App\Models\MfpDfp;
use Carbon\Carbon;
use EntityManager;
use Helpers;
use Illuminate\Console\Command;
use League\Flysystem\Exception;
use Mail;
use Psy\Exception\ErrorException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Password;
use Log;

class ProcessList extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'processlist';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';



    // TODO: what should these passwords be?!
    private $mfpPassword = "whopper1";

    private $deleted_by_id = 333333333;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        echo "\n\n";

        $send_email = true;

        // First, scan for unprocessed folders & files
        $folderpath = storage_path() . "/SplitList/";
        $folders = scandir($folderpath);

        $skipFolders = array('.', '..', '_processed');
        $folders = array_diff($folders, $skipFolders);

        if (empty($folders)) {

            exit;
        }

        $curr_dir = array_shift($folders);

        $pDir = $folderpath ."_processed/". $curr_dir;


        // Make new processed dir
        if (!file_exists($pDir)) {
            mkdir($pDir);
        }

        // move into sub-dir
        $folderpath .= $curr_dir;

        // Find Files
        $files = scandir($folderpath);

        // Move master file, if not already moved
        if (in_array("_MASTER.csv", $files)) {
            rename($folderpath."/_MASTER.csv", $pDir."/_MASTER.csv");
        }

        $skipFiles = array('.', '..', '_MASTER.csv', '_EMAILED.csv');
        $files = array_diff($files, $skipFiles);

        if (empty($files)) {
            rmdir($folderpath);
            exit;
        }

        // Process first file only
        $pFile = array_shift($files);

        // Process the CSV...
        try {

            // If this is the first "out" file, reset the data
            if (strstr($pFile, "csv_out1.csv")) {
                // Inactivate all restaurants
                echo "Resetting Restaurants: ";
                EntityManager::createQuery('UPDATE App\Models\Restaurants r SET r.deleted=CURRENT_TIMESTAMP(), r.deleted_by=' . $this->deleted_by_id)->getResult();
                echo "Done.\n";

                // Inactivate all users
                echo "Resetting Users: ";
                EntityManager::createQuery('UPDATE App\Models\User u SET u.deleted=CURRENT_TIMESTAMP(), u.deleted_by=' . $this->deleted_by_id . ' WHERE u.type <> `ADMIN`')->getResult();
                EntityManager::createQuery('DELETE FROM App\Models\Dfp d')->getResult();
                echo "Done.\n";

                // Resetting all links
                echo "Resetting Links: ";
                EntityManager::createQuery('DELETE FROM App\Models\RestaurantMfp r')->getResult();
                EntityManager::createQuery('DELETE FROM App\Models\MfpDfp m')->getResult();
                echo "Done.\n";
            }

            // Keep track of sent emails
            $sent_emails = array();
            if (($eh = fopen($pDir . "/_EMAILS.csv", "c+")) !== FALSE) {
                while (($email = fgetcsv($eh)) !== FALSE) {
                    $sent_emails[] = $email[0];
                }
                fclose($eh);
            }

            // process local file
            if (($handle = fopen($folderpath . "/" . $pFile, "r")) !== FALSE) {
                while (($data = fgetcsv($handle)) !== FALSE) {
                    // This is the header row
                    if ($data[0] == "Active Restaurant List") {
                        continue;
                    }

                    if (empty($data[0]) || empty($data[1]) || empty($data[2]) || empty($data[3]) || empty($data[4])) {
                        continue;
                    }

                    echo "-------------------------------------\n";

                    // Add restaurant to active restaurant list
                    echo "Restaurant #: " . $data[0] . "\n";
                    $rest = EntityManager::getRepository("App\Models\Restaurants")->findBy(array('rest_id' => $data[0]));

                    if (empty($rest)) {
                        echo "Restaurant not found, creating new - ";
                        $rest = new Restaurants();
                        $rest->rest_id = $data[0];
                        $rest->rest_name = $data[0];
                        $rest->deleted = null;
                        $rest->deleted_by = 0;

                        EntityManager::persist($rest);
                        EntityManager::flush();
                    } else {
                        echo "Activating Restaurant - ";
                        $rest[0]->deleted = null;
                        $rest[0]->deleted_by = 0;

                        EntityManager::flush();
                    }


                    echo "Done.\n";

                    // Get DFP user information
                    $dfp = EntityManager::getRepository("App\Models\Dfp")->findBy(array("email" => $data[4]));

                    // Create DFP user if empty (not found)
                    if (empty($dfp)) {
                        echo "Creating new DFP: " . $data[4] . "\n";

                        $dfp = new Dfp();

                        // Explode first & last name
                        $_name = explode(" ", trim($data[3]), 2);

                        if (count($_name) > 1) {
                            $dfp->name = $_name[0];
                            $dfp->lastname = $_name[1];
                        } else {
                            $dfp->name = '';
                            $dfp->lastname = '';
                            $send_email = false;
                        }

                        $dfp->email = $data[4];
                        $dfp->deleted = null;
                        $dfp->deleted_by = 0;

                        EntityManager::persist($dfp);
                        EntityManager::flush();

                        $dfp = EntityManager::getRepository("App\Models\Dfp")->findBy(array("email" => $data[4]));
                    } else {
                        echo "Activating DFP: " . $data[4] . " - ";
                        $dfp[0]->deleted = null;
                        $dfp[0]->deleted_by = 0;

                        EntityManager::flush();

                        echo "Done.\n";
                    }

                    // If we have numeric values in col 1 and 2, use DFP
                    $useMFP = true;
                    if (intval($data[1]) != 0 && intval($data[2]) != 0) {
                        $useMFP = false;
                        echo "Using DFP AS MFP...\n";
                    } else {
                        echo "Using MFP & DFP...\n";
                    }

                    // Create MFP user if DFP is MFP
                    if (!$useMFP) {

                        // Get DFP user information
                        $user_dfp = EntityManager::getRepository("App\Models\User")->findBy(array("email" => trim($data[4])));

                        if (empty($user_dfp)) {
                            echo "Creating new MFP user for DFP: " . $data[4] . "\n";

                            $user_dfp = new UserMFP();

                            // Explode first & last name
                            $_name = explode(" ", trim($data[3]), 2);

                            if (count($_name) > 1) {
                                $user_dfp->name = $_name[0];
                                $user_dfp->lastname = $_name[1];
                            } else {
                                $user_dfp->name = '';
                                $user_dfp->lastname = '';
                                $send_email = false;
                            }

                            $user_dfp->email = $data[4];
                            $user_dfp->type = "MFP";
                            $user_dfp->password = bcrypt($this->mfpPassword);
                            $user_dfp->deleted = null;
                            $user_dfp->deleted_by = 0;

                            EntityManager::persist($user_dfp);
                            EntityManager::flush();

                            $user_dfp = EntityManager::getRepository("App\Models\User")->findBy(array("email" => $data[4]));
                        } else {
                            echo "Activating DFP: " . $data[4] . " - ";
                            $user_dfp[0]->deleted = null;
                            $user_dfp[0]->deleted_by = 0;

                            EntityManager::flush();

                            echo "Done.\n";
                        }
                    }

                    // Now, on to MFP...
                    // Get MFP user information
                    if ($useMFP) {

                        $user_mfp = EntityManager::getRepository("App\Models\User")->findBy(array("email" => $data[2]));

                        if (empty($user_mfp)) {
                            echo "Creating new MFP user: " . $data[2] . " - ";

                            $user_mfp = new UserMFP();

                            // Explode first & last name
                            $_name = explode(" ", trim($data[1]), 2);

                            if (count($_name) > 1) {
                                $user_mfp->name = $_name[0];
                                $user_mfp->lastname = $_name[1];
                            } else {
                                $user_mfp->name = '';
                                $user_mfp->lastname = '';
                                $send_email = false;
                            }

                            $user_mfp->email = $data[2];
                            $user_mfp->type = "MFP";
                            $user_mfp->password = bcrypt($this->mfpPassword);
                            $user_mfp->deleted = null;
                            $user_mfp->deleted_by = 0;

                            EntityManager::persist($user_mfp);
                            EntityManager::flush();

                            $user_mfp = EntityManager::getRepository("App\Models\User")->findBy(array("email" => $data[2]));

                            echo "Done.\n";

                            // Send email to new MFPs
                            if (!in_array($user_mfp[0]->email, $sent_emails)) {
                                echo "Sending email to new MFP - ";

                                // Add text password to email
                                $user_mfp[0]->text_password = $this->mfpPassword;

                                if ($send_email)
                                {
                                    Mail::send(array("emails.welcome", "emails.txt.welcome"), ['user' => $user_mfp[0]], function ($message) use ($user_mfp)
                                    {
                                        $message->attach(storage_path() . "/app/CRT Certification Website - MFP User Guide.pdf");
                                        $message->to(Helpers::mail($user_mfp[0]->email), $user_mfp[0]->name . " " . $user_mfp[0]->lastname)->subject('Welcome to BK Foundations');
                                        $message->bcc('bkcrtcertification@originalimpressions.com', 'BK Certification');
                                    });
                                }

                                $sent_emails[] = $user_mfp[0]->email;
                                echo "Done.\n";
                            }
                        } else {
                            echo "Activating MFP user: " . $data[2] . " - ";
                            $user_mfp[0]->deleted = null;
                            $user_mfp[0]->deleted_by = 0;

                            EntityManager::flush();

                            echo "Done.\n";
                        }

                        // Send email to MFP containing active CRTs for restaurant
//                        $actCrts = EntityManager::getRepository("App\Models\Crt")->findBy(array('restNumber' => $data[0]));
                        $actCrts = EntityManager::getRepository("App\Models\Crt")->findOneBy(array('restNumber' => $data[0], 'deleted'=>null));

                        if (!empty($actCrts)) {
                            $crt_string = "";

                            foreach ($actCrts as $crt) {
                                $fname = "";
                                $lname = "";
                                try {
                                    $fname = @$crt->name;
                                    $lname = @$crt->lastname;
                                } catch (Exception $e) { }

                                $crt_string .= "{$fname} {$lname}";
                                if (!is_null(@$crt->precertified_at)) {
                                    $crt_string .= " [Pre-certified]";
                                }
                                $crt_string .= "<br>";
                            }

                            $user_mfp[0]->restNumber = $data[0];
                            $user_mfp[0]->active_crts = $crt_string;
                            $user_mfp[0]->userDM_name = "";
                            $user_mfp[0]->userDM_lastname = "";
                            $user_mfp[0]->userDM_name = @$actCrts->userDM->name;
                            $user_mfp[0]->userDM_lastname = @$actCrts->userDM->lastname;

                            echo "Sending active CRT email - ";
                            if ($send_email)
                            {
                                Mail::send(array("emails.active_crts", "emails.txt.active_crts"), ['user' => $user_mfp[0]], function ($message) use ($user_mfp)
                                {
                                    $message->to(Helpers::mail($user_mfp[0]->email), $user_mfp[0]->name . " " . $user_mfp[0]->lastname)->subject('Welcome to BK Foundations');
                                    $message->bcc('bkcrtcertification@originalimpressions.com', 'BK Certification');
                                });
                                echo "Done.\n";
                            }
                        }
                    }

                    $dfp_id = $dfp[0]->id;
                    $mfp_id = $useMFP ? $user_mfp[0]->id : $user_dfp[0]->id;

                    echo "Linking data:\n";

                    // Link Restaurant to MFP
                    $rest_mfp = new RestaurantMFP();
                    $rest_mfp->rest_id = $data[0]; // TODO: make sure restaurant is there!
                    $rest_mfp->mfp_id = $mfp_id;

                    echo "  Restaurant {$data[0]} to MFP {$mfp_id} - ";

                    EntityManager::persist($rest_mfp);
                    EntityManager::flush();

                    echo "Done.\n";

                    // Link MFP to DFP
                    $mfp_dfp = new MfpDfp();
                    $mfp_dfp->mfp_id = $mfp_id;
                    $mfp_dfp->dfp_id = $dfp_id;

                    echo "  MFP {$mfp_id} to DFP {$dfp_id} - ";

                    EntityManager::persist($mfp_dfp);
                    EntityManager::flush();
                    echo "Done.\n";

                    echo "Associating CRT to MFD & DFP - ";
                    $crts = EntityManager::getRepository('App\Models\Crt')->findBy(array("restNumber"=>$data[0]));

                    foreach ($crts as $crt) {
                        // Assign DFP
                        $c = EntityManager::getConnection()->executeUpdate('UPDATE crts SET Dfp_id=? WHERE restNumber=?', array($dfp_id, $data[0]));
                        echo "Done.\n";

                        // Assign MFP
                        $crt_mfp = EntityManager::getRepository('App\Models\UserMFP')->find($mfp_id);
                        $crt_mfp->addCrt($crt);

                        EntityManager::persist($crt_mfp);
                        EntityManager::flush();
                    }

                    echo "Done.\n";
                }
                fclose($handle);

                // Move to processed.
                rename($folderpath."/".$pFile, $pDir."/".$pFile);

                // Save list of email that were sent

                if (($eh = fopen($pDir . "/_EMAILS.csv", "c+")) !== FALSE) {
                    foreach ($sent_emails as $email) {
                        fputcsv($eh, array($email));
                    }

                    fclose($eh);
                }


                echo "-------------------------------------\n";
                echo "\n\nEnd of line.\n\n\n";
            }
        } catch (Exception $e) {
            dd($e);
        }

	}

}
