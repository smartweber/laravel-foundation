<?php

class LoginTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testBasicExample()
	{
		$response = $this->call('GET', '/auth/login');
	
		$this->assertEquals(200, $response->getStatusCode());
		
		//$this->assertViewHas('login');
	}
	
	/**
	 * @test
	 */
	public function it_shows_the_login_form()
	{
		$response = $this->call('GET', '/auth/login');
	
		$this->assertTrue($response->isOk());
	
		// Even though the two lines above may be enough,
		// you could also check for something like this:
	
		//View::shouldReceive('make')->with('login');
	}
	
	/**
	 * @test
	 */
	public function it_redirects_back_to_form_if_login_fails()
	{
		$credentials = [
		'email' => 'alexander@originalimpressions.com',
		'password' => '111111',
		];
	
		/*Auth::shouldReceive('attempt')
		->once()
		->with($credentials)
		->andReturn(false);*/
	
		$this->call('POST', 'auth/login', $credentials);
	
		$this->assertRedirectedToAction(
				'Auth\AuthController@login',
				null,
				['flash_message']
		);
	}
	
	/**
	 * @test
	 */
	public function it_redirects_to_home_page_after_user_logs_in()
	{
		$credentials = [
		'email' => 'alexander@originalimpressions.com',
		'password' => '111111',
		];
	
		/*Auth::shouldReceive('attempt')
		->once()
		->with($credentials)
		->andReturn(true);*/
	
		$this->call('POST', 'auth/login', $credentials);
	
		$this->assertRedirectedTo('home');
	}	

}