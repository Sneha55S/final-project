</div> 
<footer class="text-center py-3" style="border-top: 1px solid #34495e; background-color: #2c3e50; position: fixed; bottom: 0; width: 100%; z-index: 1020;">
    <p class="mb-0 small" style="color: #ecf0f1;">
        Cine Review &copy; <?= date("Y"); ?> | <span id="live-datetime"></span>
    </p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script>
    
    function updateLiveDateTime() {
        const now = new Date();
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true 
        };
        const dateTimeString = now.toLocaleDateString('en-US', options);
        document.getElementById('live-datetime').textContent = dateTimeString;
    }

    
    updateLiveDateTime();
    
    setInterval(updateLiveDateTime, 1000);
</script>
</body>
</html>
