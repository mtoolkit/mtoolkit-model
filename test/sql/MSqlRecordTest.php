<?php

namespace mtoolkit\model\test\sql;


use mtoolkit\model\sql\MSqlRecord;

class MSqlRecordTest extends \PHPUnit_Framework_TestCase
{
    public function testIntValues()
    {
        $array = array(
            'ColA' => '1',
            'ColB' => 1,
        );
        $record = new MSqlRecord( $array );

        $this->assertEquals( $record->getIntValue( 0 ), 1 );
        $this->assertEquals( $record->getIntValue( 'ColA' ), 1 );
        $this->assertEquals( $record->getIntValue( 1 ), 1 );
        $this->assertEquals( $record->getIntValue( 'ColB' ), 1 );
        $this->assertTrue( $record instanceof \ArrayAccess );

        foreach( $array as $key => $value )
        {
            $this->assertEquals( (int)$value, $record->getIntValue( $key ) );
            $this->assertEquals( $value, $record[$key] );
        }
    }

    public function testStringValues()
    {
        $array = array(
            'ColA' => 'ciao'
        );
        $record = new MSqlRecord( $array );

        $this->assertEquals( $record->getStringValue( 0 ), 'ciao' );
        $this->assertEquals( $record->getStringValue( 'ColA' ), 'ciao' );

        foreach( $array as $key => $value )
        {
            $this->assertTrue( is_string( $record->getStringValue( $key ) ) );
            $this->assertEquals( $value, $record->getStringValue( $key ) );
            $this->assertEquals( $value, $record[$key] );
        }
    }

    public function testBoolValues()
    {
        $array = array(
            'ColA' => 'true',
            'ColB' => true,
            'ColC' => 1,
            'ColD' => 0,
        );
        $record = new MSqlRecord( $array );

        foreach( $array as $key => $value )
        {
            $this->assertTrue( is_bool( $record->getBoolValue( $key ) ) );
            $this->assertEquals( (bool)$value, $record->getBoolValue( $key ), $key );
            $this->assertEquals( $value, $record[$key], $key );
        }
    }

    public function testFloatValues()
    {
        $array = array(
            'ColA' => '1.2',
            'ColB' => 1.2,
        );
        $record = new MSqlRecord( $array );

        $this->assertEquals( $record->getFloatValue( 0 ), 1.2);
        $this->assertEquals( $record->getFloatValue( 'ColA' ), 1.2 );
        $this->assertEquals( $record->getFloatValue( 1 ), 1.2 );
        $this->assertEquals( $record->getFloatValue( 'ColB' ), 1.2 );
        $this->assertTrue( $record instanceof \ArrayAccess );

        foreach( $array as $key => $value )
        {
            $this->assertEquals( (float)$value, $record->getFloatValue( $key ) );
            $this->assertEquals( $value, $record[$key] );
        }
    }
}
