<?php

namespace Reach\Tests\Unit;

use Reach\Deserialize;

class DeserializeTest extends UnitTest {
    public function testDate(): void {
        $actual = Deserialize::dateTime('2019-01-01');
        $this->assertEquals('2019-01-01', $actual->format('Y-m-d'));
    }

    public function testDateTime(): void {
        $actual = Deserialize::dateTime('2020-01-02 12:13:14');
        $this->assertEquals('01/02/2020 12:13:14', $actual->format('m/d/Y H:i:s'));
    }
    public function testDateTime2(): void {
        $actual = Deserialize::dateTime('2020-01-02T12:13:14Z');
        $this->assertEquals('01/02/2020 12:13:14', $actual->format('m/d/Y H:i:s'));
    }
    public function testDateTime3(): void {
        $actual = Deserialize::dateTime('2020-01-02T12:13:14.001Z');
        $this->assertEquals('01/02/2020 12:13:14', $actual->format('m/d/Y H:i:s'));
    }

    public function testNonDateTime(): void {
        $actual = Deserialize::dateTime('abc123');
        $this->assertEquals('abc123', $actual);
    }

    public function testEmpty(): void {
        $actual = Deserialize::dateTime('');
        $this->assertEquals('', $actual);
    }

    public function testNull(): void {
        $actual = Deserialize::dateTime(null);
        $this->assertNull($actual);
    }
}
