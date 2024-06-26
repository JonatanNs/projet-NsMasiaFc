<?php 

class MatchNs {

    private ? int $id = null;
    public function __construct(
                                    private NsMasia $ns_masia_id, 
                                    private RivalTeam $rivalTeam_id, 
                                    private string $home_outside, 
                                    private string $time, 
                                    private string $date
                                ) {
    }

    public function getId(): ? int {
        return $this->id;
    }
    public function setId(? int $id): void {
        $this->id = $id;
    }

    public function getNsMasiaId(): NsMasia {
        return $this->ns_masia_id;
    }

    public function setNsMasiaId(NsMasia $ns_masia_id): void {
        $this->ns_masia_id = $ns_masia_id;
    }
    public function getRivalTeamId(): RivalTeam {
        return $this->rivalTeam_id;
    }

    public function setRivalTeamId(RivalTeam $rivalTeam_id): void {
        $this->rivalTeam_id = $rivalTeam_id;
    }

    public function gethome_outside(): string {
        return $this->home_outside;
    }
    public function sethome_outside(string $home_outside): void {
        $this->home_outside = $home_outside;
    }

    public function getTime(): string {
        return $this->time;
    }
    
    public function setTime(string $time): void {
        $this->time = $time;
    }

    public function getDate(): string {
        return $this->date;
    }
    
    public function setDate(string $date): void {
        $this->date = $date;
    }
    
}