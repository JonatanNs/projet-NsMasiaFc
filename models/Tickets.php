<?php

class Tickets {

    private ? int $id = null;
    public function __construct(private string $date, private string $location, private NsMasia $nsmasia_id, private RivalsTeam $rivalTeam_id) {
    }

    public function getId(): ? int {
        return $this->id;
    }
    public function setId(? int $id): void {
        $this->id = $id;
    }

    public function getDate(): string {
        return $this->date;
    }
    public function setDate(string $date): void {
        $this->date = $date;
    }

    public function getLocation(): string {
        return $this->location;
    }
    public function setLocation(string $location): void {
        $this->location = $location;
    }

    public function getNsmasiaId(): NsMasia {
        return $this->nsmasia_id;
    }
    public function setNsmasiaId( NsMasia $nsmasia_id): void {
        $this->nsmasia_id = $nsmasia_id;
    }

    public function getRivalTeamId(): RivalsTeam {
        return $this->rivalTeam_id;
    }
    public function setRivalTeamId(RivalsTeam $rivalTeam_id):  void {
        $this->rivalTeam_id = $rivalTeam_id;
    }
}