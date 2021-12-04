<?php

class Test_TVE_Dash_AjaxController extends TD_UnitTestCase {

	public function test_param() {
		$manager   = TVE_Dash_AjaxController::instance();
		$reflector = new ReflectionClass( 'TVE_Dash_AjaxController' );
		$method    = $reflector->getMethod( 'param' );
		$method->setAccessible( true );

		$_POST['a'] = 'test';

		$result = $method->invoke( $manager, 'a' );
		$this->assertSame( 'test', $result, 'Reads from POST' );

		$result = $method->invoke( $manager, 'b', 'default_value' );
		$this->assertSame( 'default_value', $result, 'Reads default value' );

		$_REQUEST['b'] = 'test1';
		$result = $method->invoke( $manager, 'b' );
		$this->assertSame( 'test1', $result, 'Reads from REQUEST' );

		$_POST['arr'] = $expected = ['some' => 'value'];
		$result = $method->invoke( $manager, 'arr' );
		$this->assertIsArray( $result, 'Reads arrays' );
		$this->assertSame( $expected, $result, 'Reads arrays' );
	}
}
