<?php

class RivalTeam {

    private ? int $id = null;
    private ? int $ranking_points = null;
    private ? int $matchs_play = null; 
    private ? int $matchs_win = null; 
    private ? int $matchs_lose = null; 
    private ? int $matchs_nul = null;
    public function __construct(private string $team, private string $logo_url, private string $logo_alt) {
    }

    public function getId(): ? int {
        return $this->id;
    }
    public function setId(? int $id): void {
        $this->id = $id;
    }

    public function getTeam(): string {
        return $this->team;
    }
    public function setTeam(string $team): void {
        $this->team = $team;
    }

    public function getLogoUrl(): string {
        return $this->logo_url;
    }
    public function setLogoUrl(string $logo_url): void {
        $this->logo_url = $logo_url;
    }

    public function getLogoAlt(): string {
        return $this->logo_alt;
    }
    public function setLogoAlt(string $logo_alt): void {
        $this->logo_alt = $logo_alt;
    }

    public function getRankingPoints(): ? int {
        return $this->ranking_points;
    }
    public function setRankingPoints(? int $ranking_points): void {
        $this->ranking_points = $ranking_points;
    }

    public function getMatchsPlay(): ? int {
        return $this->matchs_play;
    }
    public function setMatchsPlay(? int $matchs_play): void {
        $this->matchs_play = $matchs_play;
    }

    public function getMatchsWin(): ? int {
        return $this->matchs_win;
    }
    public function setMatchsWin(? int $matchs_win): void {
        $this->matchs_win = $matchs_win;
    }

    public function getMatchsLose():? int {
        return $this->matchs_lose;
    }

    public function setMatchsLose(? int $matchs_lose): void {
        $this->matchs_lose = $matchs_lose;
    }

    public function getMatchsNul():? int {
        return $this->matchs_nul;
    }
    public function setMatchsNul(? int $matchs_nul): void {
        $this->matchs_nul = $matchs_nul;
    }
}