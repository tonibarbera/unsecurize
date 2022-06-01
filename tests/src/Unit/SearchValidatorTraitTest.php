<?php

namespace Drupal\Tests\unsecurize\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\unsecurize\Component\Trait\SearchValidatorTrait;

/**
 * Test for the Trait SearchValidatorTraitTest.
 *
 * @group unsecurize
 */
class SearchValidatorTraitTest extends UnitTestCase {

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->mocked = new MockedTrait();
  }

  /**
   * Testing empty text is not valid.
   */
  public function testEmptyTextIsNotValid() {
    $this->assertFalse($this->mocked->validSearchText(''));
  }

  /**
   * Test if detects forbidden texts.
   */
  public function testIfDectectsForbiddenStrings() {
    $this->assertFalse($this->mocked->validSearchText('@'));
    $this->assertFalse($this->mocked->validSearchText('.'));
    $this->assertFalse($this->mocked->validSearchText('%'));
    $this->assertFalse($this->mocked->validSearchText('\''));
  }

  /**
   * Test if detects strings that starts with forbidden character.
   */
  public function testIfDectectsStartByForbiddenCharacter() {
    $this->assertFalse($this->mocked->validSearchText('.something'));
    $this->assertFalse($this->mocked->validSearchText('\'a@a.a'));
  }

  /**
   * Testing if detects strings that contains forbidden character.
   */
  public function testIfDectectsContainsForbiddenCharacter() {
    $this->assertFalse($this->mocked->validSearchText('a%a.a'));
    $this->assertFalse($this->mocked->validSearchText('a@a:a'));
    $this->assertFalse($this->mocked->validSearchText('a@a:a\''));
  }

  /**
   * Testing if detects fine strings.
   */
  public function testIfDetectsValidSearchStrings() {
    $this->assertFalse($this->mocked->validSearchText('a%a.a'));
    $this->assertFalse($this->mocked->validSearchText('a@a:a'));
    $this->assertFalse($this->mocked->validSearchText('a@a:a\''));
    $this->assertTrue($this->mocked->validSearchText('a@.com'));
    $this->assertTrue($this->mocked->validSearchText('a'));
    $this->assertTrue($this->mocked->validSearchText('aa'));
    $this->assertTrue($this->mocked->validSearchText('aaa'));
    $this->assertTrue($this->mocked->validSearchText('a1'));
    $this->assertTrue($this->mocked->validSearchText('a11'));
    $this->assertTrue($this->mocked->validSearchText('a111'));
    $this->assertTrue($this->mocked->validSearchText('aaa111@aaa111.aaa'));
    $this->assertTrue($this->mocked->validSearchText('a@a.a'));
    $this->assertTrue($this->mocked->validSearchText('1@1.1'));
  }

}

/**
 * Mock class just for take the trait.
 */
class MockedTrait {
  use SearchValidatorTrait;

}
