<?php
$tutorial_title = 'Excel';
$tutorial_slug  = 'excel';
$quiz_slug      = 'excel';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Microsoft Excel is the world\'s most widely used spreadsheet application — a powerful tool for organising, calculating, and visualising data. Despite being over 40 years old, Excel remains indispensable in finance, business, science, and data analysis. Modern Excel (365/2019+) has been transformed by dynamic array functions, the LAMBDA function, and Python integration, making it more powerful than ever.</p>',
        'concepts' => [
            'Workbooks, worksheets, cells, ranges: A1 notation, named ranges',
            'Data types: numbers, text, dates/times (serial numbers), Booleans, errors',
            'Basic formulas: arithmetic operators, order of operations, =SUM, =AVERAGE, =COUNT',
            'Cell references: relative (A1), absolute ($A$1), mixed ($A1, A$1)',
            'AutoFill and Flash Fill for pattern-based data entry',
            'Sorting and filtering: single-column and multi-column sort, AutoFilter',
            'Conditional formatting: highlight cells rules, data bars, colour scales',
        ],
        'code' => [
            'title'   => 'Excel formula fundamentals',
            'lang'    => 'excel',
            'content' =>
'=SUM(B2:B100)                    ' . "// Sum a range\n" .
'=AVERAGE(B2:B100)                ' . "// Average\n" .
'=COUNTIF(C2:C100,">=90")         ' . "// Count cells meeting condition\n" .
'=SUMIF(D2:D100,"East",E2:E100)   ' . "// Sum if region = East\n" .
'=IF(A2>100,"High","Normal")      ' . "// Conditional value\n\n" .
'// Absolute reference — copy without changing the denominator
=B2/$B$101\n\n' .
'// Dynamic array — spills into multiple cells automatically (Excel 365)
=SORT(UNIQUE(A2:A100))            ' . '// Unique sorted list',
        ],
        'tips' => [
            'Use named ranges (Formulas → Name Manager) to make formulas self-documenting: =SUM(Revenue) is clearer than =SUM(B2:B100).',
            'F4 cycles through reference types when editing a formula: A1 → $A$1 → A$1 → $A1.',
            'Use Ctrl+Shift+End to jump to the last used cell — essential for understanding the extent of a dataset.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>VLOOKUP (and its modern replacement XLOOKUP) connects data across tables by looking up a value in one column and returning a corresponding value from another. Understanding lookup functions is a pivotal Excel skill that replaces dozens of manual copy-paste operations. PivotTables summarise large datasets interactively, grouping and aggregating data with drag-and-drop simplicity.</p><p>Excel charts — column, bar, line, pie, scatter — communicate data visually. Knowing which chart type matches which data story is as important as knowing how to create them.</p>',
        'concepts' => [
            'VLOOKUP: lookup_value, table_array, col_index, [range_lookup]',
            'XLOOKUP: lookup, lookup_array, return_array, [if_not_found], [match_mode]',
            'INDEX + MATCH: flexible two-dimensional lookup',
            'PivotTable: rows, columns, values, filters; group by date; calculated fields',
            'Chart types: column, bar, line, area, pie, doughnut, scatter, bubble',
            'Chart formatting: axis titles, data labels, series colours, trendlines',
            'Table feature: Ctrl+T, structured references (Table1[Column]), auto-expand',
        ],
        'code' => [
            'title'   => 'XLOOKUP vs. VLOOKUP',
            'lang'    => 'excel',
            'content' =>
'// VLOOKUP — looks RIGHT only, col_index brittle on column insert
=VLOOKUP(A2, Products!$A:$D, 3, FALSE)

// XLOOKUP — flexible, can look left, returns full range, better #N/A handling
=XLOOKUP(A2, Products!$A:$A, Products!$C:$C, "Not found", 0)

// INDEX + MATCH — the classic flexible alternative to VLOOKUP
=INDEX(Products!$C:$C, MATCH(A2, Products!$A:$A, 0))

// Two-way lookup: find value at intersection of row and column
=XLOOKUP(A2, Products!$A:$A,
    XLOOKUP(B2, Products!$1:$1, Products!$A:$Z))

// Dynamic spill: return all matching rows (Excel 365 FILTER)
=FILTER(Products!$A:$D, Products!$B:$B=A2, "No match")',
        ],
        'tips' => [
            'Prefer XLOOKUP over VLOOKUP for all new work — it handles left lookups, multiple returns, and better errors.',
            'Convert data ranges to Tables (Ctrl+T) before building PivotTables — they auto-expand as data grows.',
            'Use a scatter chart (not a line chart) when both axes are numeric and the X-axis is not evenly spaced.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Dynamic array functions (FILTER, SORT, SORTBY, UNIQUE, SEQUENCE, RANDARRAY) transform Excel from a cell-by-cell tool into a declarative data manipulation environment. A single formula can return hundreds of cells — the "spill range" — and recalculates automatically. This paradigm shift matches the power of SQL SELECT statements in a spreadsheet.</p><p>Text functions (TEXTSPLIT, TEXTJOIN, LEFT, MID, RIGHT, SUBSTITUTE, TRIM) and date functions (TODAY, NOW, DATEDIF, WORKDAY, NETWORKDAYS) handle real-world data cleaning and business date calculations.</p>',
        'concepts' => [
            'Dynamic arrays: FILTER, SORT, SORTBY, UNIQUE, SEQUENCE, RANDARRAY',
            'Spill range operator (#): =SUM(A2#) sums the spill range of A2',
            'LAMBDA function: creating custom, reusable functions without VBA',
            'LET function: naming intermediate calculations for readability and performance',
            'TEXTSPLIT, TEXTJOIN, TEXTBEFORE, TEXTAFTER (Excel 365)',
            'Date functions: DATEDIF, WORKDAY, NETWORKDAYS, EDATE, EOMONTH',
            'Array formulas (legacy Ctrl+Shift+Enter) vs. modern dynamic arrays',
        ],
        'code' => [
            'title'   => 'Dynamic array formulas',
            'lang'    => 'excel',
            'content' =>
'// FILTER: return rows where Region = "East" and Sales > 1000
=FILTER(A2:D100, (B2:B100="East") * (D2:D100>1000), "No results")

// UNIQUE + SORT: sorted list of unique product categories
=SORT(UNIQUE(C2:C100))

// SEQUENCE: generate a date range (7 days starting today)
=SEQUENCE(7, 1, TODAY(), 1)

// LET: name intermediate calculations
=LET(
  sales,  D2:D100,
  target, E2:E100,
  delta,  sales - target,
  FILTER(A2:A100, delta < 0, "All on target")
)

// LAMBDA: reusable percentage of total function
=LAMBDA(value, total, value/total * 100)(D2, SUM(D:D))',
        ],
        'tips' => [
            'Use LET to cache repeated calculations — it dramatically improves formula readability and performance.',
            'LAMBDA lets you define your own functions with a name via the Name Manager — no VBA needed.',
            'The # spill operator (=A2#) refers to the entire spill range of a dynamic array formula.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Power Query (Get & Transform Data) is Excel\'s built-in ETL tool — it connects to hundreds of data sources, cleans and transforms data through a recorded step-by-step process, and refreshes with one click. Power Pivot extends Excel with a columnar in-memory engine (xVelocity) that handles millions of rows, and DAX (Data Analysis Expressions) — a formula language for calculated columns and measures — brings data warehouse-style analytics to Excel.</p>',
        'concepts' => [
            'Power Query: connect (Excel, CSV, SQL, web, API), transform (split, pivot, unpivot, merge), load',
            'M language basics: let...in expressions, table operations, type casting',
            'Power Pivot: data model, relationships, many-to-many, inactive relationships',
            'DAX measures: SUM, CALCULATE, FILTER, ALL, RELATED, VALUES, DISTINCTCOUNT',
            'DAX time intelligence: DATEADD, SAMEPERIODLASTYEAR, TOTALYTD',
            'Data model KPIs and hierarchies in PivotTable',
            'Excel Tables as Power Query inputs for self-refreshing reports',
        ],
        'code' => [
            'title'   => 'DAX calculated measure',
            'lang'    => 'dax',
            'content' =>
"// DAX measures in Power Pivot
// Basic measure: total sales
Total Sales = SUM(Orders[Amount])

// CALCULATE to modify filter context
Sales East Region =
  CALCULATE(
    [Total Sales],
    Orders[Region] = \"East\"
  )

// Year-over-year growth %
YoY Growth % =
  VAR CurrentYear = [Total Sales]
  VAR PriorYear   = CALCULATE([Total Sales], SAMEPERIODLASTYEAR('Date'[Date]))
  RETURN
    DIVIDE(CurrentYear - PriorYear, PriorYear, 0)

// Running total (cumulative)
Cumulative Sales =
  CALCULATE(
    [Total Sales],
    FILTER(ALL('Date'), 'Date'[Date] <= MAX('Date'[Date]))
  )",
        ],
        'tips' => [
            'Power Query M steps are recorded — enable the Advanced Editor to understand and edit the M code.',
            'DAX CALCULATE is the most important function — it is the gateway to changing filter context.',
            'Use DAX measures (not calculated columns) for aggregations — measures calculate at query time and are faster.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Excel involves VBA (Visual Basic for Applications) macros for automation that goes beyond formula capabilities, Python in Excel (Microsoft 365) for data science workflows, and Office Scripts for web-based Excel automation. Understanding Excel\'s calculation engine — dependency trees, volatile functions, calculation order, and multi-threaded calculation — lets you build enormous workbooks that still calculate in milliseconds.</p>',
        'concepts' => [
            'VBA: Sub procedures, Function procedures, object model (Workbook, Worksheet, Range)',
            'VBA: loops, conditionals, error handling (On Error GoTo), UserForms',
            'Python in Excel: =PY() cell functions, pandas, matplotlib in the grid',
            'Office Scripts: TypeScript-based automation for Excel on the web and Power Automate',
            'Calculation mode: automatic, manual, automatic-except-tables; volatile functions',
            'Excel performance: avoiding volatile functions (INDIRECT, OFFSET), efficient range references',
            'Custom XML ribbon customisation: add custom tabs and buttons for user tools',
        ],
        'code' => [
            'title'   => 'VBA macro for automated report',
            'lang'    => 'vba',
            'content' =>
"Sub GenerateMonthlyReport()
    Application.ScreenUpdating = False
    Application.Calculation    = xlCalculationManual
    On Error GoTo CleanUp

    Dim wsData   As Worksheet: Set wsData   = ThisWorkbook.Sheets(\"Data\")
    Dim wsReport As Worksheet

    ' Delete old report sheet if it exists
    Application.DisplayAlerts = False
    On Error Resume Next
    ThisWorkbook.Sheets(\"Monthly Report\").Delete
    On Error GoTo CleanUp
    Application.DisplayAlerts = True

    Set wsReport = ThisWorkbook.Sheets.Add(After:=wsData)
    wsReport.Name = \"Monthly Report\"

    ' Copy and process data
    wsData.UsedRange.Copy wsReport.Range(\"A1\")
    wsReport.UsedRange.Value = wsReport.UsedRange.Value  ' paste as values
    wsReport.Columns.AutoFit

    ' Add summary table using PivotTable API
    ' (full PivotTable code omitted for brevity)

    MsgBox \"Report generated: \" & wsReport.Name, vbInformation
    CleanUp:
    Application.Calculation    = xlCalculationAutomatic
    Application.ScreenUpdating = True
    If Err.Number <> 0 Then MsgBox \"Error: \" & Err.Description
End Sub",
        ],
        'tips' => [
            'Always set Application.ScreenUpdating = False and Calculation = xlManual in VBA macros for performance.',
            'Use .Value = .Value to convert formulas to values — it is much faster than PasteSpecial for large ranges.',
            'Python in Excel is the future for data science workflows — it brings pandas and matplotlib directly into the grid.',
            'Follow the Excel blog (techcommunity.microsoft.com/t5/excel-blog) for new function announcements.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
