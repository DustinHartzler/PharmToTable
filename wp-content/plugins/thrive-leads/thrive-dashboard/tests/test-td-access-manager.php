<?php

class Test_TD_AccessManager extends TD_UnitTestCase {

	public function test_get_roles_url() {
		$manager   = TVD_AM::instance();
		$reflector = new ReflectionClass( 'TVD_AM' );
		$method    = $reflector->getMethod( 'get_roles_url' );
		$method->setAccessible( true );
		$result = $method->invoke( $manager );

		$this->assertIsArray( $result );
		$this->assertNotEmpty( $result );

		foreach ( $result as $role ) {
			$this->assertArrayHasKey( 'url', $role );
			$this->assertNotEmpty( $role['url'] );
		}

		$this->assertTrue( true );
	}
}
