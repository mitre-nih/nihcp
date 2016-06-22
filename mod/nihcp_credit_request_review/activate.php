<?php

if (get_subtype_id('object', 'alignment_commons_credits_objectives')) {
    update_subtype('object', 'alignment_commons_credits_objectives', 'Nihcp\Entity\AlignmentCommonsCreditsObjectives');
} else {
    add_subtype('object', 'alignment_commons_credits_objectives', 'Nihcp\Entity\AlignmentCommonsCreditsObjectives');
}

if (get_subtype_id('object', 'general_score')) {
    update_subtype('object', 'general_score', 'Nihcp\Entity\GeneralScore');
} else {
    add_subtype('object', 'general_score', 'Nihcp\Entity\GeneralScore');
}

if (get_subtype_id('object', 'risk_benefit_score')) {
    update_subtype('object', 'risk_benefit_score', 'Nihcp\Entity\RiskBenefitScore');
} else {
    add_subtype('object', 'risk_benefit_score', 'Nihcp\Entity\RiskBenefitScore');
}

if (get_subtype_id('object', 'final_score')) {
    update_subtype('object', 'final_score', 'Nihcp\Entity\FinalScore');
} else {
    add_subtype('object', 'final_score', 'Nihcp\Entity\FinalScore');
}

if (get_subtype_id('object', 'final_recommendation')) {
	update_subtype('object', 'final_recommendation', 'Nihcp\Entity\FinalRecommendation');
} else {
	add_subtype('object', 'final_recommendation', 'Nihcp\Entity\FinalRecommendation');
}

if (get_subtype_id('object', 'feedback')) {
	update_subtype('object', 'feedback', 'Nihcp\Entity\Feedback');
} else {
	add_subtype('object', 'feedback', 'Nihcp\Entity\Feedback');
}