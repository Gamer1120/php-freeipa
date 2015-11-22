<?php

/**
  FreeIPA library for PHP
  Copyright (C) 2015  Tobias Sette <contato@tobias.ws>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Lesser General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Lesser General Public License for more details.

  You should have received a copy of the GNU Lesser General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace FreeIPA\APIAccess;

/**
 * Class for test the user class
 * @since 0.4
 * @version 0.2
 */
class UserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * It is not test
     */
    public function setInstance($instancia)
    {
        $this->ipa = $instancia;
    }

    /**
     * It is not test
     */
    public function getInstance()
    {
        return $this->ipa;
    }
    
    /**
     * Inicializa definições dos testes e chama o método pai
     * 
     * @since 0.1
     * @todo possibilitar leitura de parâmetros
     */
    public function setUp()
    {
        $this->login['user'] = 'admin';
        $this->login['pass'] = 'Secret123';
        $this->login['host'] = 'ipa.demo1.freeipa.org';
        $this->login['cert'] = __DIR__ . "/../certs/ipa.demo1.freeipa.org_ca.crt";

        $ipa = new \FreeIPA\APIAccess\User($this->login['host'], $this->login['cert']);
        $r = $ipa->authenticate($this->login['user'], $this->login['pass']);
        $this->assertEquals(TRUE, $r['autenticado']);
        $this->setInstance($ipa);
    }
    
    public function testUserDoesNotExist()
    {
        $r = $this->getInstance()->getUser(rand(11111, 99999) . rand(11111, 99999));
        $this->assertEquals(FALSE, $r);
    }

    public function testGetLoggedUser()
    {
        $r = $this->getInstance()->getUser($this->login['user']);
        $this->assertInstanceOf('stdClass', $r);
        $this->assertTrue(is_array($r->cn));
        $this->assertTrue(is_string($r->dn));
        $this->assertTrue(is_array($r->memberof_group));
        $this->assertTrue(is_array($r->uid));
        $this->assertEquals($this->login['user'], $r->uid[0]);
    }

}