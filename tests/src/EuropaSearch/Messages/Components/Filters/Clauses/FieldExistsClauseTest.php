<?php

namespace OpenEuropa\EuropaSearch\Tests\Messages\Components\Filters\Clauses;

use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\BooleanMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\DateMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\FloatMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\IntegerMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\StringMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\Filters\Clauses\FieldExistsClause;
use OpenEuropa\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class FieldExistClauseTest.
 *
 * Tests the validation process on FieldExist.
 *
 * @package OpenEuropa\EuropaSearch\Tests\Messages\Components\Filters\Clauses
 */
class FieldExistClauseTest extends AbstractEuropaSearchTest
{

    /**
     * Test the FieldExistClause validation (success case).
     */
    public function testFieldExistsClauseValidationSuccess()
    {
        $filters = [
            'data1' => new FieldExistsClause(new StringMetadata('test_data1')),
            'data2' => new FieldExistsClause(new BooleanMetadata('test_data2')),
            'data3' => new FieldExistsClause(new IntegerMetadata('test_data3')),
            'data4' => new FieldExistsClause(new DateMetadata('test_data4')),
            'data5' => new FieldExistsClause(new FloatMetadata('test_data5')),
        ];

        $validator = $this->getDefaultValidator();

        foreach ($filters as $testedData => $filter) {
            $validationErrors = $validator->validate($filter);
            $violations = $this->getViolations($validationErrors);

            $this->assertEmpty($violations, 'FieldExistClause validation constraints are not well defined for: '.$testedData);
        }
    }

    /**
     * Test the FieldExistClause validation (failure case).
     */
    public function testFieldExistClauseValidationFailure()
    {
        $filter = new FieldExistsClause(new StringMetadata(1234));
        $validator = $this->getDefaultValidator();

        $validationErrors = $validator->validate($filter);
        $violations = $this->getViolations($validationErrors);

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/clause_violations.yml'));
        $expected = $parsedData['expectedViolations']['FieldExistsClause'];

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'FieldExistClause validation constraints are not well defined');
        }
    }
}
