<?php
$tutorial_title = 'R';
$tutorial_slug  = 'r-lang';
$quiz_slug      = 'r';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>R is a programming language and environment specifically designed for statistical computing and data visualisation. Created by Ross Ihaka and Robert Gentleman in 1993 (as a free implementation of S), R is the lingua franca of academic statistics, bioinformatics, social science research, and financial data analysis. Its CRAN (Comprehensive R Archive Network) hosts over 20,000 packages covering almost every statistical technique ever published.</p><p>This tier covers R\'s syntax, data structures, and the RStudio IDE that makes R development productive.</p>',
        'concepts' => [
            'R installation: R, RStudio, renv for reproducible environments',
            'Assignment: <- (preferred) and = operators',
            'Vectors: c(), seq(), rep(), vectorised operations',
            'Data types: numeric, integer, character, logical, factor, complex',
            'Control flow: if/else, for, while, repeat/break',
            'Functions: function(), ... (dots), default arguments, return()',
            'R console, scripts (.R), and R Markdown (.Rmd / .qmd)',
        ],
        'code' => [
            'title'   => 'R vectors and functions',
            'lang'    => 'r',
            'content' =>
'# Vectorised arithmetic — no explicit loops needed
scores <- c(78, 92, 65, 88, 74, 95)
zscore <- (scores - mean(scores)) / sd(scores)

# Named function with default arguments
summary_stats <- function(x, na.rm = TRUE) {
  list(
    n      = length(x),
    mean   = mean(x, na.rm = na.rm),
    sd     = sd(x, na.rm = na.rm),
    median = median(x, na.rm = na.rm),
    range  = range(x, na.rm = na.rm),
    iqr    = IQR(x, na.rm = na.rm)
  )
}

stats <- summary_stats(scores)
cat(sprintf("Mean: %.2f  SD: %.2f  Median: %.1f\n",
            stats$mean, stats$sd, stats$median))

# Apply family — vectorised function application
sapply(1:5, function(n) n^2)  # [1]  1  4  9 16 25',
        ],
        'tips' => [
            'Use <- for assignment inside scripts; = is conventional only for function arguments.',
            'Everything in R is a vector — scalar 42 is just a length-1 numeric vector.',
            'Run ?function_name in the console for documentation — R\'s help system is comprehensive.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Data frames are R\'s primary data structure — a table with columns of potentially different types, similar to a spreadsheet or SQL table. Base R provides read.csv, subset, merge, and aggregate for data manipulation, but the Tidyverse — a collection of packages (dplyr, tidyr, readr, ggplot2) sharing a common philosophy and pipe-friendly design — makes data manipulation dramatically more readable.</p><p>ggplot2\'s Grammar of Graphics — mapping data aesthetics to geometric objects — produces publication-quality visualisations with a consistent, composable API.</p>',
        'concepts' => [
            'Data frames: data.frame(), read.csv(), str(), summary(), head(), nrow()/ncol()',
            'Subsetting: df[rows, cols], df$column, df[["column"]]',
            'Tidyverse: tibble, dplyr verbs (select, filter, mutate, arrange, summarise, group_by)',
            'The pipe: |> (native R 4.1+) or %>% (magrittr)',
            'tidyr: pivot_longer, pivot_wider, separate, unite',
            'ggplot2: ggplot() + aes() + geom_*() + labs() + theme()',
            'readr: read_csv(), read_tsv(); readxl for Excel files',
        ],
        'code' => [
            'title'   => 'Tidyverse data manipulation and ggplot2',
            'lang'    => 'r',
            'content' =>
'library(dplyr)
library(ggplot2)

# Built-in mtcars dataset
mpg_summary <- mtcars |>
  mutate(efficiency = ifelse(mpg > 20, "high", "low")) |>
  group_by(cyl, efficiency) |>
  summarise(
    mean_mpg   = mean(mpg),
    mean_hp    = mean(hp),
    count      = n(),
    .groups    = "drop"
  ) |>
  arrange(cyl, desc(mean_mpg))

# ggplot2 visualisation
ggplot(mtcars, aes(x = wt, y = mpg, colour = factor(cyl))) +
  geom_point(size = 3, alpha = 0.7) +
  geom_smooth(method = "lm", se = FALSE) +
  labs(
    title    = "MPG vs Weight by Cylinder Count",
    x        = "Weight (1000 lbs)",
    y        = "Miles per Gallon",
    colour   = "Cylinders"
  ) +
  theme_minimal()',
        ],
        'tips' => [
            'Use the native pipe |> (R 4.1+) instead of %>% for new code — no package dependency.',
            'tibble::glimpse() gives a better overview than str() for wide data frames.',
            'Start every ggplot with ggplot(data, aes()) — all layers inherit these default aesthetics.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>R\'s statistical computing power is most visible in model fitting and inference. The <code>lm()</code> function fits linear models; <code>glm()</code> extends this to logistic, Poisson, and other generalised linear models. The <code>broom</code> package converts messy model output into tidy data frames for downstream processing and visualisation.</p><p>R Markdown and Quarto enable literate programming — mixing code, output, and prose in reproducible documents that render to HTML, PDF, Word, and presentation formats. This is the standard for academic and business reporting in R.</p>',
        'concepts' => [
            'Linear models: lm(), formula syntax (y ~ x1 + x2), summary(), coef()',
            'Model diagnostics: plot(model), residuals(), fitted(), hatvalues()',
            'Generalised linear models: glm(family = binomial()), logistic regression',
            'broom: tidy(), glance(), augment() for model output as data frames',
            'R Markdown / Quarto: code chunks, YAML front matter, knitr options',
            'purrr: map(), map2(), pmap(), reduce() for functional iteration',
            'lubridate: date parsing, arithmetic, and time zone handling',
        ],
        'code' => [
            'title'   => 'Linear model with broom and ggplot2',
            'lang'    => 'r',
            'content' =>
'library(broom)
library(dplyr)
library(ggplot2)

# Fit linear model
model <- lm(mpg ~ wt + hp + factor(cyl), data = mtcars)

# Tidy coefficient table
tidy(model, conf.int = TRUE) |>
  filter(term != "(Intercept)") |>
  ggplot(aes(x = estimate, y = term,
             xmin = conf.low, xmax = conf.high)) +
  geom_pointrange() +
  geom_vline(xintercept = 0, linetype = "dashed", colour = "red") +
  labs(title = "Coefficient plot", x = "Estimate", y = NULL)

# Model performance metrics
glance(model)   # r.squared, adj.r.squared, AIC, BIC, p.value...

# Residual diagnostics
augment(model) |>
  ggplot(aes(x = .fitted, y = .resid)) +
  geom_point() + geom_hline(yintercept = 0, linetype = "dashed") +
  labs(x = "Fitted", y = "Residual")',
        ],
        'tips' => [
            'Use broom::tidy() on every model — it makes comparing models and plotting coefficients trivial.',
            'Render Quarto documents programmatically with quarto::quarto_render() in a pipeline.',
            'purrr::map() replaces most lapply() patterns with a consistent, type-safe API.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Machine learning in R is served by the tidymodels ecosystem — a unified, tidy interface to hundreds of ML algorithms (caret\'s successor). Parsnip standardises model specifications; recipes defines preprocessing; rsample handles resampling (cross-validation, bootstrapping); yardstick measures performance. Together they create a consistent ML workflow regardless of the underlying algorithm.</p><p>Advanced visualisation with ggplot2 extensions (plotly for interactivity, patchwork for multi-plot layouts, gganimate for animated charts) and Shiny for reactive web applications complete the expert R visualisation toolkit.</p>',
        'concepts' => [
            'tidymodels: parsnip (model spec), recipes (preprocessing), workflows (combine both)',
            'rsample: initial_split(), vfold_cv(), bootstraps()',
            'tune: grid search (tune_grid), Bayesian optimisation (tune_bayes)',
            'yardstick: metric sets (rmse, roc_auc, accuracy), conf_mat()',
            'Shiny: ui (fluidPage, inputs), server (reactive(), renderPlot()), reactivity model',
            'plotly: ggplotly() for interactive ggplot2, plot_ly() native API',
            'patchwork and cowplot for multi-panel figure composition',
        ],
        'code' => [
            'title'   => 'tidymodels cross-validated model',
            'lang'    => 'r',
            'content' =>
'library(tidymodels)
tidymodels_prefer()

set.seed(42)
split <- initial_split(mtcars, prop = 0.8)
train <- training(split)
test  <- testing(split)
folds <- vfold_cv(train, v = 5)

# Recipe: preprocessing
rec <- recipe(mpg ~ ., data = train) |>
  step_normalize(all_numeric_predictors()) |>
  step_dummy(all_nominal_predictors())

# Model spec
spec <- linear_reg(penalty = tune(), mixture = tune()) |>
  set_engine("glmnet")

# Workflow
wf <- workflow() |> add_recipe(rec) |> add_model(spec)

# Tune hyperparameters
grid  <- grid_regular(penalty(), mixture(), levels = 5)
tuned <- tune_grid(wf, resamples = folds, grid = grid,
                   metrics = metric_set(rmse, rsq))

best_params <- select_best(tuned, "rmse")
final_wf    <- finalize_workflow(wf, best_params)
final_fit   <- last_fit(final_wf, split)
collect_metrics(final_fit)',
        ],
        'tips' => [
            'Use workflows to bundle recipe + model — it ensures the same preprocessing applies to train and test.',
            'Always tune hyperparameters on the training set CV folds, never on the test set.',
            'Use set.seed() before any random sampling for reproducibility.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert R involves writing high-performance R code — using vectorisation, Rcpp for C++ extensions, and data.table for in-memory data manipulation an order of magnitude faster than base R or dplyr. Understanding R\'s memory model (copy-on-modify semantics, reference objects, environments as hash tables) is essential for avoiding unexpected copies in large-data pipelines.</p><p>Package development with devtools, usethis, roxygen2 documentation, unit tests with testthat, and CI/CD with GitHub Actions completes the expert R practitioner\'s toolkit for contributing to the open-source R ecosystem.</p>',
        'concepts' => [
            'Rcpp: writing C++ functions callable from R, RcppArmadillo for linear algebra',
            'data.table: DT[i, j, by] syntax, keys, fast joins, rolling joins',
            'R memory model: copy-on-modify, tracemem(), R6 / reference classes',
            'Package development: usethis::create_package(), roxygen2, DESCRIPTION, NAMESPACE',
            'Testing: testthat, expect_equal, snapshot tests, test coverage with covr',
            'R CMD check and BiocCheck for CRAN/Bioconductor submission requirements',
            'Parallel computing: parallel, future, furrr for parallel purrr workflows',
        ],
        'code' => [
            'title'   => 'Rcpp function for performance',
            'lang'    => 'cpp',
            'content' =>
'// src/fast_zscore.cpp
#include <Rcpp.h>
using namespace Rcpp;

// [[Rcpp::export]]
NumericVector fast_zscore(NumericVector x) {
  int n = x.size();
  double mean = sum(x) / n;

  // Second pass for variance
  double var = 0.0;
  for (int i = 0; i < n; i++) {
    double diff = x[i] - mean;
    var += diff * diff;
  }
  var /= (n - 1);
  double sd = sqrt(var);

  NumericVector result(n);
  for (int i = 0; i < n; i++) {
    result[i] = (x[i] - mean) / sd;
  }
  return result;
}

// R usage after Rcpp::sourceCpp("src/fast_zscore.cpp"):
// fast_zscore(c(1, 2, 3, 4, 5))  # ~10x faster than pure R on large vectors',
        ],
        'tips' => [
            'Profile with profvis::profvis({your_code}) before reaching for Rcpp — most bottlenecks are in base R ops.',
            'data.table is 10–100× faster than dplyr for large datasets (millions of rows) — learn it for big-data R.',
            'Submit packages to CRAN early and often — the R CMD check discipline improves code quality dramatically.',
            'Follow the R-bloggers aggregator and rOpenSci for ecosystem news and package review guidelines.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
