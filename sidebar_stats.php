<?php
// Sidebar Statistics Functions
// This file contains functions to fetch real-time statistics for the admin sidebar

/**
 * Get order statistics for the sidebar
 * @param PDO $pdo Database connection
 * @return array Order statistics
 */
function getOrderStats($pdo) {
    try {
        // Get today's orders count
        $todayOrdersStmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM orders 
            WHERE DATE(created_at) = CURDATE()
        ");
        $todayOrdersStmt->execute();
        $todayOrders = $todayOrdersStmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Get today's revenue
        $todayRevenueStmt = $pdo->prepare("
            SELECT COALESCE(SUM(total_amount), 0) as revenue 
            FROM orders 
            WHERE DATE(created_at) = CURDATE() AND status = 'completed'
        ");
        $todayRevenueStmt->execute();
        $todayRevenue = $todayRevenueStmt->fetch(PDO::FETCH_ASSOC)['revenue'];
        
        // Get active users (users who have logged in within the last 30 days)
        $activeUsersStmt = $pdo->prepare("
            SELECT COUNT(DISTINCT user_id) as count 
            FROM orders 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ");
        $activeUsersStmt->execute();
        $activeUsers = $activeUsersStmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        return [
            'today_orders' => (int)$todayOrders,
            'today_revenue' => (float)$todayRevenue,
            'active_users' => (int)$activeUsers
        ];
        
    } catch (PDOException $e) {
        error_log("Error fetching order stats: " . $e->getMessage());
        return [
            'today_orders' => 0,
            'today_revenue' => 0.0,
            'active_users' => 0
        ];
    }
}

/**
 * Get order badge count (pending orders)
 * @param PDO $pdo Database connection
 * @return int Number of pending orders
 */
function getOrderBadgeCount($pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM orders 
            WHERE status = 'pending'
        ");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
        
    } catch (PDOException $e) {
        error_log("Error fetching order badge count: " . $e->getMessage());
        return 0;
    }
}

/**
 * Format currency for display
 * @param float $amount Amount to format
 * @return string Formatted currency string
 */
function formatCurrency($amount) {
    return 'LKR ' . number_format($amount, 2);
}

/**
 * Get dashboard statistics
 * @param PDO $pdo Database connection
 * @return array Dashboard statistics
 */
function getDashboardStats($pdo) {
    try {
        // Total orders
        $totalOrdersStmt = $pdo->prepare("SELECT COUNT(*) as count FROM orders");
        $totalOrdersStmt->execute();
        $totalOrders = $totalOrdersStmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Pending orders
        $pendingOrdersStmt = $pdo->prepare("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
        $pendingOrdersStmt->execute();
        $pendingOrders = $pendingOrdersStmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Completed orders
        $completedOrdersStmt = $pdo->prepare("SELECT COUNT(*) as count FROM orders WHERE status = 'completed'");
        $completedOrdersStmt->execute();
        $completedOrders = $completedOrdersStmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Cancelled orders
        $cancelledOrdersStmt = $pdo->prepare("SELECT COUNT(*) as count FROM orders WHERE status = 'cancelled'");
        $cancelledOrdersStmt->execute();
        $cancelledOrders = $cancelledOrdersStmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        return [
            'total_orders' => (int)$totalOrders,
            'pending_orders' => (int)$pendingOrders,
            'completed_orders' => (int)$completedOrders,
            'cancelled_orders' => (int)$cancelledOrders
        ];
        
    } catch (PDOException $e) {
        error_log("Error fetching dashboard stats: " . $e->getMessage());
        return [
            'total_orders' => 0,
            'pending_orders' => 0,
            'completed_orders' => 0,
            'cancelled_orders' => 0
        ];
    }
}
?>