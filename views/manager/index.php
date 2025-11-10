<?
session_start();
$_SESSION['prev_url'] = $_SERVER['REQUEST_URI'];
require_once __DIR__ . '/../../handle/auth_handle.php';
checkLogin();
isAdminLogin();
require_once __DIR__ . '/../../handle/course_handle.php';
require_once __DIR__ . '/../../handle/user_handle.php';
require_once __DIR__ . '/../../handle/topic_handle.php';
require_once __DIR__ . '/../../handle/role_handle.php';

$roles = getAllRole();
$caculUser = getCaCulUserByRole();
$calcCourseByTopic = caculCourseByTopic();

$users = getUserOrderByCreateAt();

$totalUser = getTotalUser();
$totalCourse = getTotalCourse();
$totalTopic = getTotalTopic();

$data = [];
foreach ($users as $u) {
  $date = date('Y-m-d', strtotime($u['createAt']));
  if (!isset($data[$date])) $data[$date] = 0;
  $data[$date]++;
}

$labels = json_encode(array_keys($data));
$values = json_encode(array_values($data));

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="./../../css/global.css">
  <link rel="stylesheet" href="./../../css/manager/index.css">
  <link rel="stylesheet" href="./../../fontawesome-free-7.1.0-web/css/all.min.css">

  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1"></script>
</head>

<body>
  <? include './../components/header.php' ?>
  <div class="manager grid grid-cols-5 gap-3 mt-[var(--height-header)] ">
    <? include './../components/sidebar-manager.php' ?>
    <div class="manager-container col-span-4 ">
      <div class="manager-content pt-3">
        <div class="text-[40px] tracking-wide font-bold font-sans mb-2">Dashboard</div>
        <div class="grid grid-cols-4 gap-5 mb-4">
          <div class="total-statistical">
            <div class="">
              <i class="fa-solid fa-user"></i>
            </div>
            <div class="counter" data-target="<?= $totalUser ?>">
              0
            </div>
            <div class="">
              Số người dùng
            </div>
          </div>
          <div class="total-statistical">
            <div class="">
              <i class="fa-solid fa-circle-play"></i>
            </div>
            <div class="counter" data-target="<?= $totalCourse ?>">
              0
            </div>
            <div class="">
              Số khóa học
            </div>
          </div>
          <div class="total-statistical">
            <div class="">
              <i class="fa-solid fa-table"></i>
            </div>
            <div class="counter" data-target="<?= $totalTopic ?>">
              0
            </div>
            <div class="">
              Số chủ đề
            </div>
          </div>
        </div>
        <div class="group-content mb-4 w-[calc(100%-12px)] grid grid-cols-3 gap-3">
          <div class="col-span-2">
            <canvas id="userChart" width="600" height="300"></canvas>

            <script>
              const ctxu = document.getElementById('userChart').getContext('2d');

              const userChart = new Chart(ctxu, {
                type: 'line',
                data: {
                  labels: <?= $labels ?>,
                  datasets: [{
                    label: 'Lượt đăng ký tài khoản',
                    data: <?= $values ?>,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.2)',
                    fill: true,
                    tension: 0.3
                  }]
                },
                options: {
                  plugins: {
                    title: {
                      display: true,
                      text: 'Biểu đồ lượt đăng ký tài khoản theo thời gian',
                      font: {
                        size: 18
                      }
                    },
                    zoom: {
                      zoom: {
                        wheel: {
                          enabled: true,
                          speed: 0.1,
                        },
                        pinch: {
                          enabled: true
                        },
                        mode: 'x',
                      },
                      pan: {
                        enabled: true,
                        mode: 'x'
                      }
                    }
                  },
                  scales: {
                    x: {
                      title: {
                        display: true,
                        text: 'Ngày đăng ký'
                      }
                    },
                    y: {
                      beginAtZero: true,
                      title: {
                        display: true,
                        text: 'Số lượng người dùng'
                      }
                    }
                  }
                }
              });
            </script>
          </div>
          <div class="col-span-1 flex items-center">
            <div class="w-1/2">
              <h2 class="text-gray-500 font-semibold mb-4">Phân phối người dùng</h2>
              <canvas id="donutChart"></canvas>
            </div>

            <div class="space-y-3 ml-4">
              <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                <span class="font-semibold text-gray-700"><?= $caculUser[0]['percentage'] ?>%</span>
                <span class="text-gray-400"><?= $caculUser[0]['nameRole'] ?></span>
              </div>
              <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                <span class="font-semibold text-gray-700"><?= $caculUser[1]['percentage'] ?>%</span>
                <span class="text-gray-400"><?= $caculUser[1]['nameRole'] ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="./../../js/manager/index.js"></script>
  <script>
    const ctx = document.getElementById('donutChart');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ["<?= $caculUser[0]['nameRole'] ?>", "<?= $caculUser[1]['nameRole'] ?>"],
        datasets: [{
          data: [<?= $caculUser[0]['total'] ?>, <?= $caculUser[1]['total'] ?>],
          backgroundColor: ['#1d8cf8', '#22c55e'],
          borderWidth: 0,
          cutout: '70%'
        }]
      },
      options: {
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            enabled: true,
            padding: 10,
            displayColors: false,
            callbacks: {
              label: function(context) {
                return `${context.label}: ${context.formattedValue}`;
              }
            }
          }
        },
        hover: {
          mode: 'nearest',
          intersect: false
        }
      }
    });
  </script>
</body>

</html>