<?php
// safety_plan.php — Backend for the user's Safety Plan
require_once 'config.php';
require_login();

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ── Get Safety Plan ────────────────────────────────────────────────────────
if ($action === 'get') {
    $stmt = $pdo->prepare("SELECT * FROM safety_plans WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $plan = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$plan) {
        // Return empty plan if none exists
        $plan = [
            'warning_signs' => '',
            'coping_strategies' => '',
            'people_distractions' => '',
            'people_help' => '',
            'professionals' => '',
            'environment_safe' => '',
            'reasons_to_live' => ''
        ];
    }

    echo json_encode(['success' => true, 'plan' => $plan]);
    exit;
}

// ── Save Safety Plan ───────────────────────────────────────────────────────
if ($action === 'save') {
    $user_id = $_SESSION['user_id'];
    $warning_signs = trim($_POST['warning_signs'] ?? '');
    $coping_strategies = trim($_POST['coping_strategies'] ?? '');
    $people_distractions = trim($_POST['people_distractions'] ?? '');
    $people_help = trim($_POST['people_help'] ?? '');
    $professionals = trim($_POST['professionals'] ?? '');
    $environment_safe = trim($_POST['environment_safe'] ?? '');
    $reasons_to_live = trim($_POST['reasons_to_live'] ?? '');

    // Check if exists
    $stmt = $pdo->prepare("SELECT id FROM safety_plans WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $exists = $stmt->fetch();

    if ($exists) {
        $stmt = $pdo->prepare("UPDATE safety_plans SET 
            warning_signs = ?, 
            coping_strategies = ?, 
            people_distractions = ?, 
            people_help = ?, 
            professionals = ?, 
            environment_safe = ?, 
            reasons_to_live = ? 
            WHERE user_id = ?");
        $stmt->execute([$warning_signs, $coping_strategies, $people_distractions, $people_help, $professionals, $environment_safe, $reasons_to_live, $user_id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO safety_plans 
            (user_id, warning_signs, coping_strategies, people_distractions, people_help, professionals, environment_safe, reasons_to_live) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $warning_signs, $coping_strategies, $people_distractions, $people_help, $professionals, $environment_safe, $reasons_to_live]);
    }

    echo json_encode(['success' => true, 'message' => 'Safety plan saved successfully 💚']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action.']);
?>
