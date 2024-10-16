<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation</title>

    <style>
        /* General REDCap-like page styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
        }

        h2, h3 {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            color: #333;
        }

        p, ul, li {
            font-size: 14px;
            color: #444;
            line-height: 1.5;
        }

        /* Container for content */
        .content-wrapper {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: 0 auto;
        }

        /* Style for headers */
        h2 {
            border-bottom: 2px solid #d9534f; /* REDCap red accent */
            padding-bottom: 10px;
        }

        /* Styling the button */
        button {
            background-color: #2a9ff1; /* REDCap red */
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:disabled {
            background-color: #c9302c; /* Darker color when disabled */
            cursor: not-allowed;
        }

        button:hover {
            background-color: #c9302c;
        }

        /* Spinner styling */
        .spinner {
            display: inline-block;
            width: 24px;
            height: 24px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #d9534f;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Hidden spinner by default */
        #spinner {
            display: none;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .content-wrapper {
                padding: 15px;
            }

            button {
                width: 100%;
                padding: 12px;
            }
        }

        .mcm_ft_header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .mcm_ft_left-content {
            text-align: left;
        }

        .mcm_ft_right-content {
            text-align: right;
        }
    </style>

    <script>
        // Disable the button and show spinner for 3 seconds after form submission
        function disableButtonFor3Seconds() {
            var downloadButton = document.getElementById('downloadButton');
            var spinner = document.getElementById('spinner');

            // Disable the button and show the spinner
            downloadButton.disabled = true;
            spinner.style.display = 'inline-block'; // Show spinner

            // Re-enable the button and hide the spinner after 3 seconds
            setTimeout(function() {
                downloadButton.disabled = false;
                spinner.style.display = 'none'; // Hide spinner
            }, 3000); // 3,000 milliseconds = 3 seconds
        }
    </script>
</head>
<body>

<div class="content-wrapper">
    <!-- Converted content from the Markdown file -->
    <h2>@COLUMNS Action Tag</h2>
    <h3>Overview:</h3>
    <p>The @COLUMNS action tag takes a single integer parameter and splits a set of options (checkboxes and/or radio buttons) into the specified number of columns.</p>
    <ul>
        <li>Compatible with enhanced radio buttons and checkboxes.</li>
        <li>Best when used with "left" alignment (LH or LV).</li>
        <li>When applicable, test on portable devices (smartphone and/or small tablet) to ensure layout is acceptable. If necessary, reduce the number of columns to acheive the desired presentation.</li>
        <li><strong>NOTE: </strong>The @COLUMNS action tag is not compatible with the REDCap Mobile App.</li>
    </ul>
    <h3>Usage:</h3>
    <p>Action Tag Section: @COLUMNS=5</p>

    <h3>Specifications:</h3>
    <ul>
        <li>The sorting is driven by the field alignment:
            <ul>
                <li>If a Vertical field alignment <span style="color:#e534f8;">(LV or RV)</span> is chosen, the options will be sorted vertically (first <strong>column</strong> will contain options 1, 2, 3…).</li>
                <li>If a Horizontal field alignment <span style="color:#e534f8;">(LH or RH)</span> is chosen, the options will be sorted horizontally (first <strong>row</strong> will contain options 1, 2, 3…).</li>
            </ul>
        </li>
        <li>The width of the columns is driven by the length of the labels.</li>
        <li>It is recommended that careful testing is done to ensure the menu labels and number of columns work well together and present the desired layout.</li>
        <li>Using a large number of columns may have unintended consequences in regards to the page width / layout.</li>
        <li>Works with field embedding.</li>
        <li>When downloading the instrument as a PDF, you <strong>MUST</strong> use the web browser "Save as PDF” or "Print to PDF" options. When using REDCap's built-in "Download PDF" feature, the @COLUMNS action tag is ignored.</li>
    </ul>

    <h2>Example:</h2>
    <h3>Field Configuration:</h3>
    <p>Add action tag with desired number of columns.</p>
    <p><img src="<?php echo $module->getURL('images/image2.png')?>" alt="Field Configuration"></p>

    <h3>Resulting Output (checkboxes):</h3>
    <p>Note how options are organized vertically based on the selected "vertical" alignment.</p>
    <p><img src="<?php echo $module->getURL('images/image3.png')?>" alt="Checkboxes"></p>

    <h3>Resulting Output (radio buttons):</h3>
    <p>Note how options are organized horizontally based on the selected "horizontal" alignment.</p>
    <p><img src="<?php echo $module->getURL('images/image4.png')?>" alt="Radio Buttons"></p>

    <hr>

    <div class="mcm_ft_header">
        <div class="mcm_ft_left-content">
            <strong>Johns Hopkins University, October 2024</strong>
        </div>
        <div class="mcm_ft_right-content">
            multi_column_menu_v2.1.1
        </div>
    </div>
    <hr>


    <?php

    if ($module->isSuperUser()) {
        echo '
<div class="content-wrapper">
    <!-- New Testing section with validation -->
    <h2>Testing</h2>
    <p>This section provides two resources for administrators to test the functionality of this External Module:</p>
    <ul>
        <li><strong>REDCap Data Dictionary:</strong> Use this file to create a new project in REDCap. It allows you to test the compatibility of most REDCap action tags by adding data to records and saving the results.</li>
        <li><strong>REDCap Form Zip File:</strong> This file is for importing a form into an existing project. It helps test how the module interacts with forms and action tags within existing projects.</li>
    </ul>

    <!-- Button section with flexbox -->
    <div class="download-buttons" style="display: flex; gap: 20px;">
        <!-- Button to download the CSV file -->
        <div class="download-section">
            <form method="get" action="' . $module->getURL('MultiColumnTestDataDictionary.csv') . '" onsubmit="disableButtonFor3Seconds();">
                <button id="downloadButton" type="submit">Download Testing Data Dictionary</button>
                <div id="spinner" class="spinner"></div>
            </form>
        </div>

        <!-- Button to download the ZIP file -->
        <div class="download-section">
            <form method="get" action="' . $module->getURL('MultiColumnEmTestForm.zip') . '" onsubmit="disableButtonFor3Seconds();">
                <button id="downloadButton" type="submit">Download Testing Instrument Zip File</button>
                <div id="spinner" class="spinner"></div>
            </form>
        </div>
    </div>
</div>
';
    }

    ?>
</div>

</body>
</html>
