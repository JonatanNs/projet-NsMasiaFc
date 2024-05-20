<?php

class NsMasia {

    private ? int $id = null;
    private ? int $ranking_points = 0;
    private ? int $matchs_play = 0;
    private ? int $matchs_win = 0; 
    private ? int $matchs_lose = 0;
    private ? int $matchs_nul = 0;
    public function __construct(private string $name, private string $logo_url, private string $logo_alt, private string $email,private string $stadium) {
    }

    public function getId(): ? int {
        return $this->id;
    }
    public function setId(? int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }
    public function setName(string $name): void {
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

    public function getEmail(): string {
        return $this->email;
    }
    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getLogoStadium(): string {
        return $this->stadium;
    }
    public function setLogoStadium(string $stadium): void {
        $this->stadium = $stadium;
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