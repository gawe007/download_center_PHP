<div class="d-flex flex-row align-items-center mb-2 p-1">
    <button id="btnSidebarMenu" class="btn btn-primary btn-sm align-self-start" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
            ☰ Menu
    </button>
    <div class="flex-fill">&nbsp;</div>
    <span class="username-span d-sm-none d-md-block"><?= $global['session_user_name']?></span>
    <div class="px-2">|</div>
    <?php
    $offsetSeconds = (int) (new DateTime())->getOffset(); // offset from GMT in seconds
    $offsetHours = $offsetSeconds / 3600; // convert to hours
    $gmtLabel = ($offsetHours >= 0 ? '+' : '') . $offsetHours; // format like "+7" or "-5"

    $timestamp = time(); // actual server time

    echo '<span class="server-time">Server GMT' . $gmtLabel . ' : <span id="clock"></span></span>';
    echo "<script>let serverTimestamp = {$timestamp} * 1000;</script>";
    echo "<script>let gmtLabel = '{$gmtLabel}';</script>";
    ?>

    <script>
    function pad(n) {
        return n < 10 ? '0' + n : n;
    }

    function updateClock() {
        const date = new Date(serverTimestamp);
        const hours = pad(date.getHours());
        const minutes = pad(date.getMinutes());
        const seconds = pad(date.getSeconds());

        document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
        serverTimestamp += 1000;
    }

    setInterval(updateClock, 1000);
    updateClock();
    </script>


</div>