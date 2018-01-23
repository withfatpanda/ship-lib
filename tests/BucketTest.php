<?php
namespace Tests;

use FatPanda\WordPress\Ship\Bucket\Facade as Bucket;
use PHPUnit\Framework\TestCase;

class BucketTest extends TestCase {

  function testBuckets()
  {
    $post1 = (object) [
      'ID' => 1,
      'post_title' => 'Foo',
    ];

    $post2 = (object) [
      'ID' => 2,
      'post_title' => 'Bar',
    ];

    $post3 = (object) [
      'ID' => 3,
      'post_title' => 'Ding',
    ];

    $post4 = (object) [
      'ID' => 4,
      'post_title' => 'Dong',
    ];

    do_not_repeat($post1);

    $this->assertEquals(1, Bucket::count());

    Bucket::put($post2);

    $this->assertEquals(2, Bucket::count());

    Bucket::dump();

    $this->assertEquals(0, Bucket::count());

    Bucket::put($post1, 'foo');

    $this->assertEquals(0, Bucket::count());

    $this->assertEquals(1, Bucket::count('foo'));

    Bucket::dump('foo');

    $this->assertEquals(0, Bucket::count('foo'));

    Bucket::put($post1);

    $filtered = unique([$post1, $post2, $post3]);

    $this->assertEquals(2, $filtered->count());

    $this->assertTrue($filtered->contains($post2));

    $this->assertTrue($filtered->contains($post3));

    $this->assertFalse($filtered->contains($post1));

    do_not_repeat($post2);
    do_not_repeat($post3);

    $this->assertFalse(unique([$post1, $post2, $post3]));

  }

}