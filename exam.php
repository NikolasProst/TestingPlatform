<?php
    $title = "Tests";
    include('header.php');
    include_once('functions.php');

    $test_id = $_GET['test_id'];
?>
    <div class="d-flex flex-column justify-content-center align-items-center">
        <div class="container content">
            <div class="row">
                <div class="col-lg-12 col-md-auto col-sm-12">
                    <h1>
                        <?php
                        $sql = "SELECT * FROM tests WHERE id='".$test_id."'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        echo $row['test_title'];?>
                    </h1>

                    <?php enableSingleQuestion(); ?>

                    <script>
                        var currentTab = 0; // Current tab is set to be the first tab (0)
                        showTab(currentTab); // Display the current tab
                        function showTab(n) {
                            // This function will display the specified tab of the form...
                            var x = document.getElementsByClassName("tab");
                            x[n].style.display = 'block';
                            //... and fix the Previous/Next buttons:
                            if (n == (x.length - 1)) {
                                document.getElementById("nextBtn").innerHTML = "Завершить";
                            } else {
                                document.getElementById("nextBtn").innerHTML = "Следующий вопрос";
                            }
                        }
                        function nextPrev(n) {
                            // This function will figure out which tab to display
                            var x = document.getElementsByClassName("tab");
                            // Hide the current tab:
                            x[currentTab].style.display = "none";
                            // Increase or decrease the current tab by 1:
                            currentTab = currentTab + n;
                            // if you have reached the end of the form...
                            if (currentTab >= x.length) {
                                // ... the form gets submitted:
                                document.getElementById("examForm").submit();
                                return false;
                            }
                            // Otherwise, display the correct tab:
                            showTab(currentTab);
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
        <?php include('footer.php');

