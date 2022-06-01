<?php

namespace Drupal\Tests\unsecurize\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\unsecurize\Service\SearchUsersPrivateData;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher;

/**
 * Test for the Service SearchUsersPrivateData.
 *
 * @group unsecurize
 */
class SearchUsersPrivateDataTest extends UnitTestCase {

  /**
   * SearchUsersPrivateData Mock.
   *
   * @var MockBuilder
   */
  protected $service;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $configFactoryInterface = $this->getMockBuilder(ConfigFactoryInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    $configFactoryMock = new ConfigFactoryMock();
    $configFactoryInterface->expects($this->any())
      ->method('get')
      ->willReturn($configFactoryMock);

    $entityTypeManager = $this->getMockBuilder(EntityTypeManager::class)
      ->disableOriginalConstructor()
      ->getMock();

    $entityTypeMock = new EntityTypeMock();
    $entityTypeManager->expects($this->any())
      ->method('getStorage')
      ->willReturn($entityTypeMock);

    $eventDispatcher = $this->getMockBuilder(ContainerAwareEventDispatcher::class)
      ->disableOriginalConstructor()
      ->getMock();

    $this->service = new SearchUsersPrivateData($configFactoryInterface, $entityTypeManager, $eventDispatcher);
  }

  /**
   * Testing not valid input text then returns empty array.
   */
  public function testEmptySearchTextIsNotValidReturnsEmptyArray() {
    $this->assertIsArray($this->service->searchUser(''));
    $this->assertEmpty($this->service->searchUser(''));
    $this->assertIsArray($this->service->searchUser('@'));
    $this->assertEmpty($this->service->searchUser('@'));
    $this->assertIsArray($this->service->searchUser('.'));
    $this->assertEmpty($this->service->searchUser('.'));
    $this->assertIsArray($this->service->searchUser('%'));
    $this->assertEmpty($this->service->searchUser('%'));
    $this->assertIsArray($this->service->searchUser('\''));
    $this->assertEmpty($this->service->searchUser('\''));
    $this->assertIsArray($this->service->searchUser('.something'));
    $this->assertEmpty($this->service->searchUser('.something'));
    $this->assertIsArray($this->service->searchUser('\'a@a.a'));
    $this->assertEmpty($this->service->searchUser('\'a@a.a'));
    $this->assertIsArray($this->service->searchUser('a%a.a'));
    $this->assertEmpty($this->service->searchUser('a%a.a'));
    $this->assertIsArray($this->service->searchUser('a@a:a'));
    $this->assertEmpty($this->service->searchUser('a@a:a'));
    $this->assertIsArray($this->service->searchUser('a@a:a\''));
    $this->assertEmpty($this->service->searchUser('a@a:a\''));
  }

  /**
   * Testing valid results.
   */
  public function testSearchUser() {
    $this->assertIsArray($this->service->searchUser('foo'));
    $this->assertNotEmpty($this->service->searchUser('foo'));
  }

}

/**
 * Mocked ConfigFactoryMock class.
 */
class ConfigFactoryMock {

  /**
   * Mocked function.
   */
  public function get($foo) {
    return '1';
  }

}

/**
 * Mocked EntityTypeMock class.
 */
class EntityTypeMock {

  /**
   * Mocked function.
   */
  public function getStorage() {
    return $this;
  }

  /**
   * Mocked function.
   */
  public function getQuery() {
    return $this;
  }

  /**
   * Mocked function.
   */
  public function execute() {
    return [];
  }

  /**
   * Mocked function.
   */
  public function loadMultiple() {
    $user = new MockUser();
    return [$user];
  }

}

/**
 * Mocked User Class.
 */
class MockUser {

  /**
   * Mocked function.
   */
  public function get($foo) {
    $bar = new \stdClass();
    $bar->value = "foo";
    return $bar;
  }

}
