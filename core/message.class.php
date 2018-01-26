<?php
class Message {

    private $classes, $message, $detail;

    public function getClasses(): string {
        return $this->classes;
    }

    public function setClasses(string $classes) {
        $this->classes = $classes;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function setMessage(string $message) {
        $this->message = $message;
    }

    public function getDetail(): ?string {
        return $this->detail;
    }

    public function setDetail(?string $detail) {
        $this->detail = $detail;
    }

}
