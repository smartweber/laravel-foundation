<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon;
use Mail;

class SplitList extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'splitlist';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Split BK Restaurant list into 1,000 row files.';

	private $ftpServer	= "ftp01.originalimpressions.com";
	private $ftpPort	= "21";
	private $ftpUser	= "bkcrtinbound";
	private $ftpPassword = "whopper1";
	private $ftpDir		= "/";

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
		// Connect to FTP and look for files
		if ($connId = ftp_connect($this->ftpServer, $this->ftpPort)) {
			if (ftp_login($connId, $this->ftpUser, $this->ftpPassword)) {

				ftp_pasv($connId, true);
				$filenames = ftp_nlist($connId, $this->ftpDir);

				foreach ($filenames as $file) {
					try {
						// Save file locally
						$mytime = Carbon::now();
						$folderpath = storage_path() . "/SplitList/" . $mytime->format('Ymd_His');
						mkdir( $folderpath  );
						$masterfile = $folderpath . "/_MASTER.csv";
						$localfile = $folderpath . "/" . substr($file, 1);

						ftp_get($connId, $masterfile, substr($file, 1), FTP_ASCII);

						$in = fopen($masterfile, 'r');

						$outputFile = $localfile."_out";

						$rowCount = 0;
						$fileCount = 0;
						$splitSize = 1000;

						while (!feof($in)) {
							if (($rowCount % $splitSize) == 0) {
								if ($rowCount > 0) {
									fclose($out);
								}
								$out = fopen($outputFile . ++$fileCount . '.csv', 'w');
							}
							$data = fgetcsv($in);
							if ($data)
								fputcsv($out, $data);
							$rowCount++;
						}

						fclose($out);

						// Remove file from FTP server
						ftp_delete($connId, $file);

						// Send alert email that a file was found and split
						$rowCount--; // to match spread sheet
						$msgText = "CRT list file found for processing" . PHP_EOL;
						$msgText.= "Date: " . $mytime->format("M d, Y h:s") . PHP_EOL;
						$msgText.= "Name: " . $file . PHP_EOL;
						$msgText.= "Number of Lines: " . $rowCount . PHP_EOL;

						Mail::raw($msgText, function ($message) {
							$message->to('rkomatz@originalimpressions.com');
							$message->cc('sean@originalimpressions.com');
							$message->cc('senglar@whopper.com');
							$message->from('donotreply@originalimpressions.com', "CRT");
							$message->subject("CRT MFP/DFP Restaurant List Processing");
						});
					}
					catch (Exception $e) {
						Log::error($e->getMessage());
					}
				}
			}
		}
	}
}
