<?php
/**
 * Plugin Name: KPI Dashboard
 * Description: Adds a KPI menu page with dynamic Projects and Tasks fields, including checkboxes and date selectors.
 * Version: 1.3
 * Author: Suraj Karmakar
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Hook to add the KPI menu in the admin panel
add_action('admin_menu', 'kpi_add_admin_menu');

function kpi_add_admin_menu() {
    add_menu_page(
        'KPI Dashboard',       // Page title
        'KPI',                 // Menu title
        'manage_options',      // Capability
        'kpi-dashboard',       // Menu slug
        'kpi_dashboard_page',  // Function to render page
        'dashicons-chart-line',// Icon
        20                     // Position in the menu
    );
}

// Function to display the KPI page content
function kpi_dashboard_page() {
    // Fetch saved projects and tasks
    $projects = get_option('kpi_projects', []);
    $projects = is_array($projects) ? $projects : [];
    ?>
    <div class="wrap">
        <h1>KPI Dashboard</h1>
        <p>Manage your projects and tasks dynamically.</p>

        <form method="post" action="">
            <?php wp_nonce_field('kpi_save_data', 'kpi_nonce'); ?>

            <div id="project-container">
                <?php
                if (!empty($projects)) {
                    foreach ($projects as $project_index => $project) {
                        $tasks = $project['tasks'];
                        echo '<div class="project-section">
                            <input type="text" name="projects[' . $project_index . '][name]" value="' . esc_attr($project['name']) . '" placeholder="Project Name">
                            <button type="button" class="remove-project button button-primary">Remove Project</button>
                            <div class="project-wrapper">
                                <div class="task-container">';
                                    if (!empty($tasks)) {
                                        foreach ($tasks as $task_index => $task) {
                                            echo '<div class="task-field" data-index="' . $task_index . '">
                                                <input type="checkbox" name="projects[' . $project_index . '][tasks][' . $task_index . '][completed]" ' . (isset($task['completed']) && $task['completed']=='completed' ? 'checked' : '') . ' disabled>
                                                <input type="text" name="projects[' . $project_index . '][tasks][' . $task_index . '][name]" value="' . esc_attr($task['name']) . '" placeholder="Task Name" required>
                                                <input type="date" name="projects[' . $project_index . '][tasks][' . $task_index . '][expected_start_date]" value="' . esc_attr($task['expected_start_date'] ?? '') . '" required>
                                                <input type="date" name="projects[' . $project_index . '][tasks][' . $task_index . '][expected_end_date]" value="' . esc_attr($task['expected_end_date'] ?? '') . '" required>
                                                <input type="date" name="projects[' . $project_index . '][tasks][' . $task_index . '][actual_end_date]" value="' . esc_attr($task['actual_end_date'] ?? '') . '">
                                                <input type="range" min="0" max="100" step="1"
                                                    name="projects[' . $project_index . '][tasks][' . $task_index . '][completion_percentage]"
                                                    value="' . esc_attr($task['completion_percentage'] ?? 0) . '"
                                                    oninput="this.nextElementSibling.value = this.value">
                                                <output>' . esc_attr($task['completion_percentage'] ?? 0) . '</output>%
                                                <button type="button" class="remove-task button button button-primary">Remove</button>
                                            </div>';
                                        }
                                    }
                                echo '</div>
                                    <p class="completion-percentage">';
                                        if (!empty($tasks)){
                                            $completionPercentages = array_column($tasks, 'completion_percentage');
                                            $average = round(count($completionPercentages) > 0 ? array_sum($completionPercentages) / count($completionPercentages) : 0 , 1 );
                                            echo $average;
                                        }
                                    echo '</p>
                                    <div class="completion-position">';

                                    if (!empty($tasks)){
                                        // Get the current date
                                        $currentDate = date('Y-m-d');

                                        // Extract expected_end_date as key and actual_end_date as value
                                        $expectedEndDates = array_column($tasks, 'expected_end_date');
                                        $actualEndDates = array_column($tasks, 'actual_end_date');

                                        // Replace empty actual_end_date with the current date
                                        foreach ($actualEndDates as &$actualDate) {
                                            if (empty($actualDate)) {
                                                $actualDate = $currentDate;
                                            }
                                        }

                                        // Combine expected_end_date as key and updated actual_end_date as value
                                        $resultArray = array_combine($expectedEndDates, $actualEndDates);

                                        $statusArray = [];

                                        foreach ($resultArray as $expected => $actual) {
                                            // Calculate difference in days
                                            $daysDifference = ceil((strtotime($expected) - strtotime($actual)) / (60 * 60 * 24));
                                            $statusArray[$expected] = $daysDifference;
                                        }
                                        $average_status = array_sum($statusArray);
                                        if ($average_status > 0) {
                                            echo '<svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M6 8L2 8L2 6L8 5.24536e-07L14 6L14 8L10 8L10 16L6 16L6 8Z" fill="#00FF00"></path> </g></svg>';
                                        } elseif ($average_status < 0) {
                                            echo '<svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" transform="rotate(180)"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M6 8L2 8L2 6L8 5.24536e-07L14 6L14 8L10 8L10 16L6 16L6 8Z" fill="#FF0000"></path> </g></svg>';
                                        } else {
                                            echo '<svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" transform="rotate(90)"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M6 8L2 8L2 6L8 5.24536e-07L14 6L14 8L10 8L10 16L6 16L6 8Z" fill="#FFFF00"></path> </g></svg>';
                                        }
                                    }

                                    echo '</div>
                            </div>
                            <button type="button" class="add-task button">Add Task</button>
                        </div>';
                    }
                }
                ?>
            </div>

            <button type="button" id="add-project" class="button">Add Project</button>
            <input type="submit" name="save_kpi_data" value="Save Data" class="button button-primary">
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const projectContainer = document.getElementById('project-container');
        const addProjectButton = document.getElementById('add-project');

        // Function to add a new project
        addProjectButton.addEventListener('click', function () {
            let projectIndex = document.querySelectorAll('.project-section').length;
            let projectHTML = `
                <div class="project-section">
                    <input type="text" name="projects[${projectIndex}][name]" placeholder="Project Name">
                    <button type="button" class="remove-project button button-primary">Remove Project</button>
                    <div class="task-container"></div>
                    <button type="button" class="add-task button">Add Task</button>
                </div>
            `;
            projectContainer.insertAdjacentHTML('beforeend', projectHTML);
        });

        // Function to remove a project
        projectContainer.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-project')) {
                event.target.parentElement.remove();
            }
        });

        // Function to add a task inside a project
        projectContainer.addEventListener('click', function (event) {
            if (event.target.classList.contains('add-task')) {
                let projectSection = event.target.parentElement;
                let taskContainer = projectSection.querySelector('.task-container');
                let projectIndex = Array.from(projectContainer.children).indexOf(projectSection);

                // Get the last task's data-index and increment it
                let lastTask = taskContainer.querySelector('.task-field:last-child');
                let lastIndex = lastTask ? parseInt(lastTask.getAttribute('data-index')) : -1;
                let taskIndex = lastIndex + 1;
                let taskHTML = `
                    <div class="task-field" data-index="${taskIndex}">
                        <input type="checkbox" name="projects[${projectIndex}][tasks][${taskIndex}][completed]" disabled>
                        <input type="text" name="projects[${projectIndex}][tasks][${taskIndex}][name]" placeholder="Task Name" required>
                        <input type="date" name="projects[${projectIndex}][tasks][${taskIndex}][expected_start_date]" required>
                        <input type="date" name="projects[${projectIndex}][tasks][${taskIndex}][expected_end_date]" required>
                        <input type="date" name="projects[${projectIndex}][tasks][${taskIndex}][actual_end_date]">
                        <input type="range" min="0" max="100" step="1"
                            name="projects[${projectIndex}][tasks][${taskIndex}][completion_percentage]"
                            value="0"
                            oninput="this.nextElementSibling.value = this.value">
                        <output>0</output>%
                        <button type="button" class="remove-task button button-primary">Remove</button>
                    </div>
                `;
                taskContainer.insertAdjacentHTML('beforeend', taskHTML);
            }
        });

        // Function to remove a task
        projectContainer.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-task')) {
                event.target.parentElement.remove();
            }
        });
    });
    </script>

    <style>
    .task-container { margin-top: 10px; }
    .project-section { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; background: #f9f9f9; }
    .task-field { display: flex; align-items: center; margin-bottom: 10px; }
    .task-field input { margin-right: 10px; }
    .remove-task { margin-left: 10px !important; }
    input[type=checkbox]:disabled { opacity: 1 !important; }
    .project-wrapper { display: flex; justify-content: space-around; align-items: center; }
    .completion-position { width: 50px; }
    .completion-percentage { font-size: xxx-large; margin: 0; }
    </style>

    <?php
}

// Save Projects and Tasks Data
add_action('admin_init', 'kpi_save_project_data');

function kpi_save_project_data() {
    if (isset($_POST['save_kpi_data']) && check_admin_referer('kpi_save_data', 'kpi_nonce')) {
        $projects = isset($_POST['projects']) ? array_map(function ($project) {
            return [
                'name'  => sanitize_text_field($project['name']),
                'tasks' => isset($project['tasks']) ? array_values(array_filter(array_map(function ($task) {
                    $task_name = sanitize_text_field($task['name'] ?? '');
                    $expected_start_date = sanitize_text_field($task['expected_start_date'] ?? '');
                    $expected_end_date = sanitize_text_field($task['expected_end_date'] ?? '');
                    $actual_end_date = sanitize_text_field($task['actual_end_date'] ?? '');
                    $completion_percentage = isset($task['completion_percentage']) ? intval($task['completion_percentage']) : 0;

                    // Ignore tasks that don't have all required fields
                    if (empty($task_name) || empty($expected_start_date) || empty($expected_end_date)) {
                        return null;
                    }

                    // Validate that expected_start_date is before expected_end_date
                    if (strtotime($expected_start_date) >= strtotime($expected_end_date)) {
                        return null; // Ignore task if start date is not older than end date
                    }

                    return [
                        'name' => $task_name,
                        'completed' => $completion_percentage=='100' && !empty($actual_end_date)? 'completed' : 'incomplete',
                        'expected_start_date' => $expected_start_date,
                        'expected_end_date' => $expected_end_date,
                        'actual_end_date' => $actual_end_date,
                        'completion_percentage' => $completion_percentage,
                    ];
                }, $project['tasks'] ?? []))) : [],
            ];
        }, $_POST['projects']) : [];

        update_option('kpi_projects', $projects);
    }
}
?>
