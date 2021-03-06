<?php

namespace Biz\Question\Type;

class UncertainChoice extends BaseQuestion implements TypeInterface
{
    public function create($fields)
    {
    }

    public function update($id, $fields)
    {
    }

    public function delete($id)
    {
    }

    public function get($id)
    {
    }

    public function judge($question, $answer)
    {
        if (0 == count(array_diff($question['answer'], $answer)) && 0 == count(array_diff($answer, $question['answer']))) {
            return array('status' => 'right', 'score' => $question['score']);
        }

        if (0 == count(array_diff($answer, $question['answer']))) {
            $percentage = intval(count($answer) / count($question['answer']) * 100);

            return array(
                'status' => 'partRight',
                'percentage' => $percentage,
                'score' => $question['missScore'],
            );
        }

        return array('status' => 'wrong', 'score' => 0);
    }

    public function filter(array $fields)
    {
        if (!empty($fields['choices'])) {
            $fields['metas'] = array('choices' => $fields['choices']);
        }

        return parent::filter($fields);
    }

    public function getAnswerStructure($question)
    {
        return $question['metas']['choices'];
    }

    public function analysisAnswerIndex($question, $userAnswer)
    {
        return array($question['id'] => $userAnswer['answer']);
    }
}
