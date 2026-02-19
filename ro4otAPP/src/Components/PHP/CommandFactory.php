<?php 
// IT DOES'T HAVE SENSE HERE :> 
/*
class CommandFactory {
    public static function make(array $data): CommandInterface {
        if (!isset($data['category'], $data['order'])) {
            throw new Exception("Nieprawidłowy format danych komendy.");
        }

        switch ($data['category']) {
            case 'Move':
                return new MoveCommand($data['order']);
            case 'System':
                return new SystemCommand($data['order']);
            case 'Detectors':
                return new DetectorsCommand($data['order']);
            case 'Other':
                return new OtherCommand($data['order']);
            default:
                throw new Exception("Nieznana kategoria: " . $data['category']);
        }
    }
}
*/