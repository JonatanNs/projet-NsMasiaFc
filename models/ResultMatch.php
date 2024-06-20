<?php

class ResultMatch {
    private ? int $id = null;
    public function __construct(private array|MatchNs $match_id,private int $score_nsMasia, private int $score_rivalTeam) {
    }

    public function getId(): ? int {
        return $this->id;
    }
    public function setId(? int $id): void {
        $this->id = $id;
    }

    public function getMatchId(): array|MatchNs {
        return $this->match_id;
    }

    public function setMatchId( array|MatchNs $match_id): void {
        $this->match_id = $match_id;
    }

    public function getScoreNsMasia(): int {
        return $this->score_nsMasia;
    }

    public function setScoreNsMasia(int $score_nsMasia): void {
        $this->score_nsMasia = $score_nsMasia;
    }

    public function getScoreRivalTeam(): int {
        return $this->score_rivalTeam;
    }
    public function setScoreRivalTeam(int $score_rivalTeam): void {
        $this->score_rivalTeam = $score_rivalTeam;
    }
}