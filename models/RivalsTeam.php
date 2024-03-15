<?php

class RivalsTeam {

    private ? int $id = null;
    private ? int $ranking_points = null;
    private ? int $match_play = null; 
    private ? int $match_win = null; 
    private ? int $match_lose = null; 
    private ? int $match_nul = null;
    public function __construct(private string $name, private string $logo_url, private string $logo_alt) {
    }

    public function getId(): ? int {
        return $this->id;
    }
    public function setId(? int $id): void {
        $this->id = $id;
    }

    public function getTeam(): string {
        return $this->name;
    }
    public function setTeam(string $name): void {
        $this->name = $name;
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

    public function getMatchWin(): ? int {
        return $this->match_win;
    }
    public function setMatchWin(? int $match_win): void {
        $this->match_win = $match_win;
    }

    public function getMatchesLose():? int {
        return $this->match_lose;
    }

    public function setMatchesLose(? int $match_lose): void {
        $this->match_lose = $match_lose;
    }

    public function getMatchesNul():? int {
        return $this->match_nul;
    }
    public function setMatchesNul(? int $match_nul): void {
        $this->match_nul = $match_nul;
    }
}