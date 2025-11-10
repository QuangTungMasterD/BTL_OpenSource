<?php
session_start();
require_once __DIR__ . '/../../../handle/auth_handle.php';
checkLogin();
isAdminLogin();
require_once __DIR__ . '/../../../handle/topic_handle.php';

$search = $_GET['s'] ?? '';
$totalPage = getTotalPageTopic(htmlspecialchars($search));

if (isset($_GET['page'])) {
  $page = $_GET['page'] < $totalPage ? $_GET['page'] : $totalPage;
  $page = $page < 1 ? 1 : $page;
} else {
  $page = 1;
}

$topics = handleGetTopicByPage(htmlspecialchars($search), $page);
$total = count($topics);

$totalTopic = count(handleGetTopicByPage('', $totalPage > 0 ? $totalPage : 1)) + 40 * (($totalPage - 1 >= 0 ? ($totalPage - 1) : 0));

$_SESSION['prev_url'] = $_SERVER['REQUEST_URI'];
unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="http://localhost/BTL-N2/css/global.css">
  <link rel="stylesheet" href="http://localhost/BTL-N2/css/manager/list.css">
  <link rel="stylesheet" href="http://localhost/BTL-N2/css/manager/topics/index.css">
  <link rel="stylesheet" href="http://localhost/BTL-N2/fontawesome-free-7.1.0-web/css/all.min.css">

  <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
  <script src="./../../../js/manager/topics/index.js"></script>
  <title>Quản lý chủ đề</title>
</head>

<body>
  <? require_once '../../components/header.php'; ?>
  <div class="manager grid grid-cols-5 gap-3 mt-[var(--height-header)] ">
    <? include './../../components/sidebar-manager.php' ?>
    <div class="manager-container col-span-4 ">
      <div class="manager-content pt-3">
        <div class="text-[40px] tracking-wide font-bold font-sans mb-2">Danh sách chủ đề</div>
        <div class="group-content w-[calc(100%-12px)]">
          <div class="action mt-1 mb-6 flex">
            <div class="flex flex-1">
              <a href="./add.php" class="btn primary flex items-center"><i class="fa-solid fa-plus"></i> Thêm chủ đề</a>
            <form class="relative" action="./../../../handle/topic_handle.php" method="GET">
              <input type="hidden" name="action" value="search">
              <input name="s" value="<?php echo isset($_GET['s']) ? htmlspecialchars($_GET['s']) : ''; ?>" type="text" class="input-search" placeholder="Tìm kiếm chương học">
              <button class="absolute w-[30px] h-[30px] text-sm rounded-[50%] bg-[rgb(var(--primary-color))] text-white right-2 top-2" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
            </div>
            <div class=""><p class="btn outline flex items-center !cursor-default"><?=$totalTopic?> chủ đề</p></div>
          </div>
          <div class="table-content">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
              <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                  <tr>
                    <th scope="col" class="px-6 py-3">
                      STT
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Tên chủ đề
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Màu chủ đề
                    </th>
                    <th scope="col" class="px-6 py-3">
                      <span class="sr-only">Hành động</span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <? if ($total == 0) { ?>
                    <tr>
                      <td colspan="5" class="text-center py-4 text-gray-500">Không tìm thấy bình luận nào.</td>
                    </tr>
                  <? }; ?>

                  <?
                  $index = 1;
                  for ($i = 0; $i < $total; $i++) {
                    if ($i < $total - 1) {
                  ?>
                      <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                          <?=($index++)?>
                        </th>
                        <td class="px-6 py-4">
                          <?= htmlspecialchars($topics[$i]['nameTopic']) ?>
                        </td>
                        <td class="px-6 py-4">
                          <p class="topic" style="background-color: rgb(<?=htmlspecialchars($topics[$i]['color'])?>); color: white"><?= htmlspecialchars($topics[$i]['color']) ?></p>
                        </td>
                        <td class="px-6 py-4 text-right action-object">
                          <a href="./edit.php?id=<?= $topics[$i]['idTopic'] ?>" class=""><i class="fa-solid fa-pen-to-square action-object-item"></i></a>
                          <!-- <a href="#" class=""><i class="fa-solid fa-circle-info action-object-item"></i></a> -->
                          <button
                            data-modal-target="popup-modal"
                            data-modal-toggle="popup-modal"
                            data-topic-id="<?= htmlspecialchars($topics[$i]['idTopic']) ?>"
                            class="open-delete-modal text-red-600 hover:text-red-800">
                            <i class="fa-solid fa-trash action-object-item"></i>
                          </button>
                        </td>
                      </tr>
                    <?
                    }
                    if ($i == $total - 1) {
                    ?>
                      <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                          <?=($index++)?>
                        </th>
                        <td class="px-6 py-4">
                          <?= htmlspecialchars($topics[$i]['nameTopic']) ?>
                        </td>
                        <td class="px-6 py-4">
                          <p class="topic" style="background-color: rgb(<?=htmlspecialchars($topics[$i]['color'])?>); color: white"><?= htmlspecialchars($topics[$i]['color']) ?></p>
                        </td>
                        <td class="px-6 py-4 text-right action-object">
                          <a href="./edit.php?id=<?= $topics[$i]['idTopic'] ?>" class=""><i class="fa-solid fa-pen-to-square action-object-item"></i></a>
                          <!-- <a href="#" class=""><i class="fa-solid fa-circle-info action-object-item"></i></a> -->
                          <button
                            data-modal-target="popup-modal"
                            data-modal-toggle="popup-modal"
                            data-topic-id="<?= htmlspecialchars($topics[$i]['idTopic']) ?>"
                            class="open-delete-modal text-red-600 hover:text-red-800">
                            <i class="fa-solid fa-trash action-object-item"></i>
                          </button>
                        </td>
                      </tr>
                  <?
                    }
                  } ?>
                </tbody>
              </table>
            </div>
            <div class="pagination mt-3 flex items-center justify-center max-w-[388px] mx-auto ">
              <!-- Phân trang -->
              <?
              if ($page > 1) {
              ?>
                <a href="?<?= htmlspecialchars($search) != '' ? 's=' . htmlspecialchars($search) . '&' : '' ?>page=<?= ($page - 1) ?>" class="btn ghost pagination-item text-[18px]! text-[rgb(var(--primary-color))]!">
                  <i class="fa-solid fa-caret-left"></i>
                </a>
              <? } ?>
              <?
              $maxVisible = 6;
              if ($page == 1) {
                $endRenderPage = min($page + 5, $totalPage);
              } elseif ($page == 2) {
                $endRenderPage = min($page + 4, $totalPage);
              } else {
                $endRenderPage = min($page + 3, $totalPage);
              }
              $startRenderPage = max(1, $endRenderPage - ($maxVisible - 1));

              ?>
              <div class="pagination-list max-w-[304px] mx-2 flex items-center justify-start overflow-hidden">
                <?
                for ($startRenderPage; $startRenderPage <= $endRenderPage; $startRenderPage++) {
                ?>
                  <?
                  if ($startRenderPage == $page) {
                  ?>
                    <a
                      href="#"
                      class="btn primary pagination-item">
                      <?= $startRenderPage ?>
                    </a>
                  <?
                  } else {
                  ?>
                    <a href="?<?= htmlspecialchars($search) != '' ? 's=' . htmlspecialchars($search) . '&' : '' ?>page=<?= $startRenderPage ?>" class="btn outline pagination-item"><?= $startRenderPage ?></a>
                  <? } ?>
                <? } ?>
              </div>
              <?
              if ($totalPage > $page) {
              ?>
                <a href="?<?= htmlspecialchars($search) != '' ? 's=' . htmlspecialchars($search) . '&' : '' ?>page=<?= ($page + 1) ?>" class="btn ghost pagination-item text-[18px]! text-[rgb(var(--primary-color))]!"><i class="fa-solid fa-caret-right"></i></a>
              <?
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--  -->

  <div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
        <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center">
          <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          <h3 id="content-modal-delete" class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400"></h3>
          <a data-modal-hide="popup-modal" type="button" id="confirm-delete" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
            Xóa
          </a>
          <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Hủy</button>
        </div>
      </div>
    </div>
  </div>
  <!--  -->
</body>

</html>