<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Tests\Extension\Core\Type;

use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Intl\Util\IntlTestHelper;

class LanguageTypeTest extends BaseTypeTest
{
    const TESTED_TYPE = 'Symfony\Component\Form\Extension\Core\Type\LanguageType';

    protected function setUp()
    {
        IntlTestHelper::requireIntl($this, false);

        parent::setUp();
    }

    public function testCountriesAreSelectable()
    {
        $choices = $this->factory->create(static::TESTED_TYPE)
            ->createView()->vars['choices'];

        $this->assertContainsEquals(new ChoiceView('en', 'en', 'English'), $choices);
        $this->assertContainsEquals(new ChoiceView('en_GB', 'en_GB', 'British English'), $choices);
        $this->assertContainsEquals(new ChoiceView('en_US', 'en_US', 'American English'), $choices);
        $this->assertContainsEquals(new ChoiceView('fr', 'fr', 'French'), $choices);
        $this->assertContainsEquals(new ChoiceView('my', 'my', 'Burmese'), $choices);
    }

    /**
     * @requires extension intl
     */
    public function testChoiceTranslationLocaleOption()
    {
        $choices = $this->factory
            ->create(static::TESTED_TYPE, null, [
                'choice_translation_locale' => 'uk',
            ])
            ->createView()->vars['choices'];

        // Don't check objects for identity
        $this->assertContains(new ChoiceView('en', 'en', 'англійська'), $choices, '', false, false);
        $this->assertContains(new ChoiceView('en_US', 'en_US', 'англійська (США)'), $choices, '', false, false);
        $this->assertContains(new ChoiceView('fr', 'fr', 'французька'), $choices, '', false, false);
        $this->assertContains(new ChoiceView('my', 'my', 'бірманська'), $choices, '', false, false);
    }

    public function testMultipleLanguagesIsNotIncluded()
    {
        $choices = $this->factory->create(static::TESTED_TYPE, 'language')
            ->createView()->vars['choices'];

        $this->assertNotContainsEquals(new ChoiceView('mul', 'mul', 'Mehrsprachig'), $choices);
    }

    public function testSubmitNull($expected = null, $norm = null, $view = null)
    {
        parent::testSubmitNull($expected, $norm, '');
    }

    public function testSubmitNullUsesDefaultEmptyData($emptyData = 'en', $expectedData = 'en')
    {
        parent::testSubmitNullUsesDefaultEmptyData($emptyData, $expectedData);
    }

    /**
     * @group legacy
     */
    public function testInvalidChoiceValuesAreDropped()
    {
        $type = new LanguageType();

        $this->assertSame([], $type->loadChoicesForValues(['foo']));
    }
}
