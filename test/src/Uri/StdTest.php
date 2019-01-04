<?php
/*
 * This file is part of the Salut framework
 * (c) 2017 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ra5k\Salud\Test\Uri;

// [imports]
use Ra5k\Salud\Uri;
use Ra5k\Salud\Test\BaseTestCase;

/**
 * TODO: Make sure all cases described in the java.net.URI description
 * of #resolve() and #relativize() are covered by the tests.
 *
 *
 */
class StdTest extends BaseTestCase
{

    public function testRender1()
    {
        $uri = new Uri\Std(['scheme' => 'http', 'host' => 'localhost', 'port' => 4711]);
        $this->assertEquals("http://localhost:4711", $uri->toString());
    }

    public function testRender2()
    {
        $uri = new Uri\Std(['port' => 4711]);
        $this->assertEquals("//:4711", $uri->toString());
    }

    public function testRender3()
    {
        $uri = new Uri\Std(['path' => '/xyz']);
        $this->assertEquals("/xyz", $uri->toString());
    }

    public function testRender4()
    {
        $uri = new Uri\Std(['host' => '[::1]']);
        $this->assertEquals("//[::1]", $uri->toString());
    }

    public function testParse1()
    {
        $uri = new Uri\Std('http://[::1]:4711');
        $this->assertEquals("[::1]", $uri->host());
    }

    public function testNormalize1()
    {
        $base = new Uri\Std("/abc/def/../xyz");
        $uri = $base->normalize();
        $this->assertEquals("/abc/xyz", $uri->toString());
    }

    public function testNormalize2()
    {
        $base = new Uri\Std("/abc/../../xyz");
        $uri = $base->normalize();
        $this->assertEquals("/../xyz", $uri->toString());
    }
    
    public function testNormalize3()
    {
        $base = new Uri\Std("abc/..");
        $uri = $base->normalize();
        $this->assertEquals("", $uri->toString());
    }
    
    public function testResolve1()
    {
        $base = new Uri\Std("//localhost/pub/beer");
        $uri = $base->resolve("gin");
        $this->assertEquals("//localhost/pub/gin", $uri->toString());
    }

    public function testResolve2()
    {
        $base = new Uri\Std("/pqr/stu");
        $uri = $base->resolve("abc/xyz?q=1");
        $this->assertEquals("/pqr/abc/xyz?q=1", $uri->toString());
    }

    public function testResolve3()
    {
        $base = new Uri\Std("abc");
        $uri = $base->resolve("?q=1");
        $this->assertEquals("?q=1", $uri->toString());
    }

    public function testResolve4()
    {
        $base = new Uri\Std("john:doe@localhost/abc");
        $uri = $base->resolve("jane:lee@localhost");
        $this->assertEquals("jane:lee@localhost", $uri->toString());
    }

    public function testResolve5()
    {
        $base = new Uri\Std("/abc/def/../xyz");
        $uri = $base->resolve("?q=42");
        $this->assertEquals("/abc/?q=42", $uri->toString());
    }

    public function testResolve6()
    {
        $base = new Uri\Std("http://john:doe@example.com/abc/?q=42");
        $uri = $base->resolve("#top");
        $this->assertEquals("http://john:doe@example.com/abc/?q=42#top", $uri->toString());
    }

    public function testRelativize1()
    {
        $base = new Uri\Std("http://example.com/abc");
        $uri = $base->relativize("http://example.com/abc/def");
        $this->assertEquals("def", $uri->toString());
    }

    public function testRelativize2()
    {
        $base = new Uri\Std("/abc");
        $uri = $base->relativize("/abc/def");
        $this->assertEquals("def", $uri->toString());
    }

    public function testRelativize3()
    {
        $base = new Uri\Std("http:/abc");
        $uri = $base->relativize("http:/abc/def");
        $this->assertEquals("def", $uri->toString());
    }

    public function testRelativize4()
    {
        $base = new Uri\Std("http://localhost");
        $uri = $base->relativize("http://localhost?q=42");
        $this->assertEquals("?q=42", $uri->toString());
    }

}
