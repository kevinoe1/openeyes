<?php

/**
 * Study Trait
 *
 * This trait provides shared functionality that will be found across all studies.
 */
trait Study {

    /**
     * @param User $user
     *
     *  @return bool
     */
    public function canBeProposedByUser(CWebUser $user)
    {
        if (new DateTime($this->end_date) < new DateTime('midnight')) {
            return false;
        }

        foreach ($this->proposers as $proposer) {
            if ($proposer->id === $user->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns a list of studies a subject is participating in.
     *
     * Requires the name of the pivot table being used to be set in the class.
     *
     * @param BaseActiveRecord  $subject
     *
     * @return array
     */
    public function participatingStudyIds(BaseActiveRecord $subject)
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('subject_id = ?');
        $criteria->select = 'study_id';
        $criteria->params = array($subject->id);
        $existing_studies = $this->getCommandBuilder()
            ->createFindCommand($this->pivot, $criteria)
            ->queryAll();

        $ids = array();
        if ($existing_studies) {
            foreach ($existing_studies as $study) {
                $ids[] = $study['study_id'];
            }
        }

        return $ids;
    }
}