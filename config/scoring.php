<?php

return [
    'fixture_evaluators' => [
        \App\Scoring\Evaluators\ResultEvaluator::class,
        \App\Scoring\Evaluators\ScoreMarginEvaluator::class,
    ],
    'competition_evaluators' => [
        \App\Scoring\Evaluators\GoalscorerEvaluator::class,
        \App\Scoring\Evaluators\TrophyEvaluator::class,
    ]
];