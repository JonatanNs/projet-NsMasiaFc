<?php

class Articles {

    private ? int $id = null;
    public function __construct(private string $title, private string $excerpt, private string $content, private string $date, private string $img_url, private string $img_alt) {
    }

    public function getId(): ? int {
        return $this->id;
    }
    public function setId(? int $id): void {
        $this->id = $id;
    }

    public function getTitle(): string {
        return $this->title;
    }
    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function getExcerpt(): string {
        return $this->excerpt;
    }
    public function setExcerpt(string $excerpt): void {
        $this->excerpt = $excerpt;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function setContent(string $content): void {
        $this->content = $content;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function setDate(string $date): void {
        $this->date = $date;
    }

    public function getImgUrl(): string {
        return $this->img_url;
    }
    public function setImgUrl(string $img_url): void {
        $this->img_url = $img_url;
    }

    public function getImg_alt(): string {
        return $this->img_alt;
    }
    public function setImg_alt(string $img_alt): void {
        $this->img_alt = $img_alt;
    }
}
