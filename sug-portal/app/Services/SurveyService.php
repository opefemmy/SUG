<?php

namespace App\Services;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use Illuminate\Support\Facades\DB;
use Exception;

class SurveyService
{
    /**
     * Create a survey with associated questions and options.
     */
    public function createSurvey(array $data, array $questions): Survey
    {
        return DB::transaction(function () use ($data, $questions) {
            $survey = Survey::create($data);

            foreach ($questions as $qData) {
                $question = SurveyQuestion::create([
                    'survey_id' => $survey->id,
                    'question_text' => $qData['text'],
                    'question_type' => $qData['type'],
                    'order' => $qData['order'] ?? 0,
                ]);

                if (!empty($qData['options'])) {
                    foreach ($qData['options'] as $optionText) {
                        \App\Models\SurveyOption::create([
                            'survey_question_id' => $question->id,
                            'option_text' => $optionText,
                        ]);
                    }
                }
            }

            return $survey;
        });
    }

    /**
     * Submit a survey response.
     */
    public function submitResponse(int $surveyId, array $answers, ?int $userId = null): SurveyResponse
    {
        return DB::transaction(function () use ($surveyId, $answers, $userId) {
            $survey = Survey::findOrFail($surveyId);

            // Check if survey is still open
            if (now()->gt($survey->ends_at)) {
                throw new Exception("This survey has already ended.");
            }

            // Determine if we should track the user
            $finalUserId = $survey->is_anonymous ? null : $userId;

            $response = SurveyResponse::create([
                'survey_id' => $surveyId,
                'user_id' => $finalUserId,
            ]);

            foreach ($answers as $questionId => $answerValue) {
                // Handle multiple choice (option_id) vs text (answer_text)
                $isOption = is_numeric($answerValue);

                SurveyAnswer::create([
                    'survey_response_id' => $response->id,
                    'survey_question_id' => $questionId,
                    'answer_text' => $isOption ? null : $answerValue,
                    'survey_option_id' => $isOption ? $answerValue : null,
                ]);
            }

            return $response;
        });
    }
}
