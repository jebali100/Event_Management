<?php
require_once 'db.php';

class Participation {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
        error_log("Participation class initialized with connection to evenements_db");
    }

    public function getByParticipantId($participant_id) {
        try {
            error_log("getByParticipantId called with participant_id: " . (is_array($participant_id) ? json_encode($participant_id) : $participant_id));
            $sql = "SELECT p.*, e.* FROM participation p 
                    JOIN evenement e ON p.id_evenement = e.id 
                    WHERE p.id_participant = ?";
            $params = [$participant_id];
            error_log("Executing SQL: $sql with params: " . json_encode($params));
            
            if ($this->conn) {
                $stmt = $this->conn->prepare($sql);
                $stmt->execute($params);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                error_log("Result from PDO: " . json_encode($result));
                return $result;
            }
            return [];
        } catch (Exception $e) {
            error_log("Error in getByParticipantId: " . $e->getMessage());
            return [];
        }
    }

    public function add($participant_id, $event_id) {
        try {
            error_log("Attempting to add participation: participant_id=$participant_id, event_id=$event_id");
            // Validate input
            if (!is_numeric($participant_id) || $participant_id <= 0 || !is_numeric($event_id) || $event_id <= 0) {
                throw new Exception("Invalid participant_id ($participant_id) or event_id ($event_id)");
            }
            $sql = "INSERT INTO participation (id_participant, id_evenement, date_inscription) VALUES (?, ?, CURDATE())";
            $params = [$participant_id, $event_id];
            error_log("Executing SQL: $sql with params: " . var_export($params, true));
            if (method_exists($this->db, 'execute')) {
                $result = $this->db->execute($sql, $params);
                error_log("add() execute result: " . var_export($result, true));
                if ($result === false || $result === null) {
                    error_log("add() failed via execute: execute() returned false or null. Falling back to PDO.");
                    if ($this->conn) {
                        $stmt = $this->conn->prepare($sql);
                        $result = $stmt->execute($params);
                        error_log("add() PDO fallback result: " . var_export($result, true));
                        if ($result === false) {
                            $errorInfo = $stmt->errorInfo();
                            error_log("add() PDO fallback failed: SQLSTATE[{$errorInfo[0]}]: {$errorInfo[2]}");
                        }
                        return $result;
                    } else {
                        error_log("add() PDO fallback failed: No PDO connection available");
                        return false;
                    }
                } else {
                    error_log("add() succeeded via execute");
                    return true;
                }
            } else {
                error_log("execute() method not found in Database class. Using PDO fallback.");
                if ($this->conn) {
                    $stmt = $this->conn->prepare($sql);
                    $result = $stmt->execute($params);
                    error_log("add() PDO fallback result: " . var_export($result, true));
                    if ($result === false) {
                        $errorInfo = $stmt->errorInfo();
                        error_log("add() PDO fallback failed: SQLSTATE[{$errorInfo[0]}]: {$errorInfo[2]}");
                    }
                    return $result;
                } else {
                    throw new Exception("No suitable method (execute or PDO) available.");
                }
            }
        } catch (Exception $e) {
            error_log("Error in add: " . $e->getMessage());
            return false;
        }
    }

    public function remove($participant_id, $event_id) {
        try {
            error_log("Attempting to remove participation: participant_id=$participant_id, event_id=$event_id");
            $sql = "DELETE FROM participation WHERE id_participant = ? AND id_evenement = ?";
            if (method_exists($this->db, 'execute')) {
                $result = $this->db->execute($sql, [$participant_id, $event_id]);
                error_log("remove() execute result: " . var_export($result, true));
                return $result;
            } else {
                error_log("execute() method not found in Database class. Using PDO fallback.");
                if ($this->conn) {
                    $stmt = $this->conn->prepare($sql);
                    $result = $stmt->execute([$participant_id, $event_id]);
                    error_log("remove() PDO fallback result: " . var_export($result, true));
                    if ($result === false) {
                        $errorInfo = $stmt->errorInfo();
                        error_log("remove() PDO fallback failed: SQLSTATE[{$errorInfo[0]}]: {$errorInfo[2]}");
                    }
                    return $result;
                } else {
                    throw new Exception("No suitable method (execute or PDO) available.");
                }
            }
        } catch (Exception $e) {
            error_log("Error in remove: " . $e->getMessage());
            return false;
        }
    }

    public function isParticipating($participant_id, $event_id) {
        try {
            error_log("Checking participation: participant_id=$participant_id, event_id=$event_id");
            $sql = "SELECT COUNT(*) FROM participation WHERE id_participant = ? AND id_evenement = ?";
            if (method_exists($this->db, 'execute')) {
                $stmt = $this->db->execute($sql, [$participant_id, $event_id]);
                if (is_object($stmt) && method_exists($stmt, 'fetch')) {
                    $row = $stmt->fetch(PDO::FETCH_NUM);
                    $count = $row ? $row[0] : 0;
                    error_log("isParticipating count: $count");
                    return $count > 0;
                } else {
                    error_log("execute() did not return a fetchable result. Using PDO fallback.");
                    if ($this->conn) {
                        $stmt = $this->conn->prepare($sql);
                        $stmt->execute([$participant_id, $event_id]);
                        $row = $stmt->fetch(PDO::FETCH_NUM);
                        $count = $row ? $row[0] : 0;
                        error_log("isParticipating PDO fallback count: $count");
                        return $count > 0;
                    } else {
                        throw new Exception("No suitable method (execute or PDO) available.");
                    }
                }
            } else {
                error_log("execute() method not found in Database class. Using PDO fallback.");
                if ($this->conn) {
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute([$participant_id, $event_id]);
                    $row = $stmt->fetch(PDO::FETCH_NUM);
                    $count = $row ? $row[0] : 0;
                    error_log("isParticipating PDO fallback count: $count");
                    return $count > 0;
                } else {
                    throw new Exception("No suitable method (execute or PDO) available.");
                }
            }
        } catch (Exception $e) {
            error_log("Error in isParticipating: " . $e->getMessage());
            return false;
        }
    }
}
?>