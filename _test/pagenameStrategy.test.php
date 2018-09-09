<?php

namespace dokuwiki\plugin\yearbox\test;

use dokuwiki\plugin\yearbox\services\pageNameStrategies\PageNameStrategy;

/**
 * Tests from syntax to html for the yearbox plugin
 *
 * @group plugin_yearbox
 * @group plugins
 */
class pagenameStrategy_plugin_yearbox_test extends \DokuWikiTest {


    public function dataProvider()
    {
        return [
            [
                '',
                '',
                'day',
                ':2018-03:day-2018-03-08',
                'Test default configutation',
            ],
            [
                'separatedCompletely',
                'bar',
                null,
                'bar:2018:03:08',
                'test completely separated namespaces'
            ],
            [
                'YearMonthSeperatedNS',
                '',
                '',
                ':2018:03:2018-03-08',
                'have year and month as separate ns, but keep the pageid the iso-date',
            ],
            [
                'YearNS',
                'appreciation',
                '',
                'appreciation:2018:2018-03-08',
                'have a year namespace and the iso-date as pade id'
            ],
        ];

    }

    /**
     * @dataProvider dataProvider
     *
     * @param string $strategyName
     * @param string $baseNS
     * @param string $name
     * @param string $expectedPageId
     * @param        $msg
     */
    public function test_pagenameStrategy($strategyName, $baseNS, $name, $expectedPageId, $msg)
    {
        $year = 2018;
        $month = '03';
        $day = '08';

        $strategy = PageNameStrategy::getPagenameStategy($strategyName);

        $actual_id = $strategy->getPageId($baseNS, $year, $month, $day, $name);

        $this->assertSame($expectedPageId, $actual_id, $msg);
    }
}
