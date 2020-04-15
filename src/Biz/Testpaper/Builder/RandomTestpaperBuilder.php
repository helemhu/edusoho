<?php

namespace Biz\Testpaper\Builder;

use Codeages\Biz\Framework\Context\Biz;
use Codeages\Biz\ItemBank\Assessment\Service\AssessmentService;
use PhpOffice\PhpWord\Exception\Exception;

class RandomTestpaperBuilder implements TestpaperBuilderInterface
{
    protected $biz;

    public function __construct(Biz $biz)
    {
        $this->biz = $biz;
    }

    public function build($fields)
    {
        list($range, $sections) = $this->getRangeAndSections($fields);

        $sections = $this->getAssessmentService()->drawItems($range, $sections);
        $sections = $this->setSectionQuestionScore($sections);

        $assessment = array(
            'bank_id' => $fields['itemBankId'],
            'name' => $fields['name'],
            'displayable' => 1,
            'description' => $fields['description'],
            'sections' => $sections,
        );

        return $this->getAssessmentService()->createAssessment($assessment);
    }

    public function canBuild($options)
    {
        list($range, $sections) = $this->getRangeAndSections($options);

        try {
            $this->getAssessmentService()->drawItems($range, $sections);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function showTestItems($testId, $resultId = 0, $options = array())
    {
    }

    public function updateSubmitedResult($resultId, $usedTime, $options = array())
    {
    }

    protected function getRangeAndSections($fields)
    {
        $range = array(
            'bank_id' => $fields['itemBankId'],
            'category_ids' => empty($fields['ranges']['categoryId']) ? array() : array($fields['ranges']['categoryId']),
        );

        $sections = array();
        foreach ($fields['sections'] as $type => $section) {
            $section = array(
                'conditions' => array(
                    'item_types' => array($type),
                ),
                'item_count' => $section['count'],
                'name' => $section['name'],
                'score' => $fields['scores'][$type],
            );

            if (!empty($fields['missScores'][$type])) {
                $section['miss_score'] = $fields['missScores'][$type];
            }

            if ('difficulty' == $fields['mode']) {
                $section['conditions']['distribution'] = $fields['percentages'];
            }

            $sections[] = $section;
        }

        return array($range, $sections);
    }

    protected function setSectionQuestionScore($sections)
    {
        foreach ($sections as &$section) {
            foreach ($section['items'] as &$item) {
                foreach ($item['questions'] as &$question) {
                    $question['score'] = $section['score'];
                    if (!empty($section['miss_score'])) {
                        $question['miss_score'] = $section['miss_score'];
                    }
                }
            }
        }

        return $sections;
    }

    /**
     * @return AssessmentService
     */
    protected function getAssessmentService()
    {
        return $this->biz->service('ItemBank:Assessment:AssessmentService');
    }
}
