<?php
$tutorial_title = 'Data Science';
$tutorial_slug  = 'data-science';
$quiz_slug      = 'data-science';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Data science is the interdisciplinary field that uses statistical methods, algorithms, and programming to extract knowledge and insights from structured and unstructured data. A data scientist bridges mathematics, statistics, programming (Python/R), domain expertise, and communication skills to turn raw data into actionable decisions. The Python ecosystem — NumPy, pandas, matplotlib, scikit-learn, and Jupyter notebooks — is the standard toolkit.</p>',
        'concepts' => [
            'Data science roles: data analyst, data engineer, data scientist, ML engineer',
            'The data science process: CRISP-DM (business understanding → deployment)',
            'Jupyter notebooks: cells, kernels, markdown, magic commands (%timeit, %%time)',
            'Data types: structured (tabular), semi-structured (JSON, XML), unstructured (text, images)',
            'Descriptive statistics: mean, median, mode, variance, std, percentiles, skewness',
            'Data visualisation: bar, line, scatter, histogram, box plot, heatmap',
            'Python data science stack: NumPy, pandas, matplotlib, seaborn, scikit-learn',
        ],
        'code' => [
            'title'   => 'Exploratory data analysis workflow',
            'lang'    => 'python',
            'content' =>
"import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns

sns.set_theme(style='whitegrid', palette='muted')

df = pd.read_csv('sales_data.csv', parse_dates=['date'])

# Shape and types
print(df.shape, df.dtypes, df.head())

# Missing values
print(df.isnull().mean().sort_values(ascending=False))

# Descriptive statistics
print(df.describe(include='all'))

# Distribution of numeric variables
df.select_dtypes(include='number').hist(bins=30, figsize=(15, 10))
plt.tight_layout(); plt.show()

# Time series: monthly revenue
monthly = df.resample('ME', on='date')['revenue'].sum()
monthly.plot(title='Monthly Revenue', figsize=(12, 4))
plt.ylabel('Revenue (\\$)'); plt.show()

# Correlation heatmap
corr = df.select_dtypes(include='number').corr()
sns.heatmap(corr, annot=True, fmt='.2f', cmap='coolwarm', vmin=-1, vmax=1)",
        ],
        'tips' => [
            'Always run df.info() and df.describe() first — they reveal type mismatches and unexpected ranges.',
            'Use df.isnull().mean() to see missing rate per column — columns with >50% missing are usually dropped.',
            'Use parse_dates in read_csv to automatically convert date columns to datetime dtype.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Statistical hypothesis testing enables data scientists to make data-driven decisions with quantifiable confidence. A/B tests, t-tests, chi-square tests, and ANOVA are the workhorses of business experimentation. Understanding p-values, confidence intervals, statistical power, and effect sizes separates rigorous analysis from misleading conclusions.</p><p>Regression analysis — simple and multiple linear regression — provides both a predictive model and an interpretable understanding of feature relationships with the target variable.</p>',
        'concepts' => [
            'Hypothesis testing: null hypothesis, alternative hypothesis, p-value, significance level',
            'Type I (false positive) and Type II (false negative) errors, statistical power',
            'Common tests: t-test (means), chi-square (independence), ANOVA (multiple groups)',
            'Effect size: Cohen\'s d, r², practical vs. statistical significance',
            'A/B testing: sample size calculation, experiment duration, multiple testing correction',
            'Confidence intervals: interpretation, bootstrap confidence intervals',
            'Regression: OLS, coefficient interpretation, R², adjusted R², residual analysis',
        ],
        'code' => [
            'title'   => 'A/B test analysis',
            'lang'    => 'python',
            'content' =>
"import numpy as np
import pandas as pd
from scipy import stats
from statsmodels.stats.power import TTestIndPower

# Sample size calculation before experiment
analysis = TTestIndPower()
n = analysis.solve_power(
    effect_size=0.2,    # minimum detectable effect
    alpha=0.05,         # significance level
    power=0.8,          # 80% chance of detecting true effect
)
print(f'Required sample size per group: {n:.0f}')

# Analyse experiment results
control   = pd.Series([12.3, 11.8, 13.1, 12.7, 11.5])  # conversion rate %
treatment = pd.Series([13.5, 14.2, 12.9, 13.8, 14.1])

# Independent samples t-test
t_stat, p_value = stats.ttest_ind(control, treatment)
effect_size = (treatment.mean() - control.mean()) / control.std()

print(f'Control mean:   {control.mean():.2f}%')
print(f'Treatment mean: {treatment.mean():.2f}%')
print(f'Relative lift:  {(treatment.mean()/control.mean()-1)*100:.1f}%')
print(f't-statistic:    {t_stat:.3f}')
print(f'p-value:        {p_value:.4f}')
print(f\"Cohen's d:      {effect_size:.3f}\")",
        ],
        'tips' => [
            'Calculate required sample size before launching an A/B test — under-powered tests miss real effects.',
            'A statistically significant result does not mean practically important — always calculate effect size.',
            'Apply Bonferroni correction (α/n) when running multiple hypothesis tests simultaneously.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Advanced pandas operations — pivot tables, window functions, group-by aggregations, multi-index, and complex merge/join patterns — enable data wrangling at scale. Interactive visualisation with Plotly and dashboarding with Streamlit democratise data insights by letting data scientists build interactive web applications without frontend engineering expertise.</p>',
        'concepts' => [
            'Advanced pandas: groupby + transform, pivot_table, melt/stack/unstack',
            'Window functions: rolling, expanding, ewm for time-series analytics',
            'Multi-index: hierarchical index, xs(), unstack(), level management',
            'Merging and joining: merge types, suffixes, indicator column, validate',
            'Plotly Express and Plotly Graph Objects: interactive charts',
            'Streamlit: st.dataframe, st.plotly_chart, st.sidebar, st.cache_data',
            'Dash (Plotly): callback architecture, Input/Output, layout components',
        ],
        'code' => [
            'title'   => 'Streamlit interactive data dashboard',
            'lang'    => 'python',
            'content' =>
"import streamlit as st
import pandas as pd
import plotly.express as px

st.set_page_config(page_title='Sales Dashboard', layout='wide')
st.title('Sales Analytics Dashboard')

@st.cache_data
def load_data():
    df = pd.read_csv('sales.csv', parse_dates=['date'])
    df['month'] = df['date'].dt.to_period('M').astype(str)
    return df

df = load_data()

# Sidebar filters
with st.sidebar:
    st.header('Filters')
    regions  = st.multiselect('Region', df['region'].unique(), df['region'].unique())
    date_rng = st.date_input('Date range', [df['date'].min(), df['date'].max()])

filtered = df[
    df['region'].isin(regions) &
    (df['date'] >= pd.Timestamp(date_rng[0])) &
    (df['date'] <= pd.Timestamp(date_rng[1]))
]

col1, col2 = st.columns(2)
with col1:
    monthly = filtered.groupby('month')['revenue'].sum().reset_index()
    st.plotly_chart(px.line(monthly, x='month', y='revenue', title='Monthly Revenue'))
with col2:
    by_region = filtered.groupby('region')['revenue'].sum().reset_index()
    st.plotly_chart(px.bar(by_region, x='region', y='revenue', title='Revenue by Region'))",
        ],
        'tips' => [
            'Use @st.cache_data for any expensive data loading — without it, Streamlit re-runs everything on every interaction.',
            'Use plotly.express for quick charts; plotly.graph_objects for full customisation.',
            'Export Streamlit dashboards to Streamlit Community Cloud for free public hosting.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Production data science requires scalable data processing beyond single-machine pandas. PySpark processes data across clusters; Dask parallelises pandas operations on multi-core machines; Polars provides near-Spark performance in a single process. SQL (BigQuery, Redshift, Snowflake) is often the most scalable option for analytical queries against cloud data warehouses.</p>',
        'concepts' => [
            'Dask: dask.dataframe, dask.array, delayed execution, task graphs',
            'Polars: lazy evaluation, Expr API, 10–100× faster than pandas for many operations',
            'PySpark: RDDs, DataFrames, SparkSQL, MLlib; Databricks platform',
            'Cloud data warehouses: BigQuery, Redshift, Snowflake — architecture and SQL dialect differences',
            'dbt (data build tool): models, tests, documentation, lineage graph',
            'Time-series analysis: statsmodels (ARIMA, Prophet), rolling statistics',
            'Geospatial data: GeoPandas, Folium, deck.gl, Google Maps API',
        ],
        'code' => [
            'title'   => 'Polars vs. pandas performance',
            'lang'    => 'python',
            'content' =>
"import polars as pl
import pandas as pd
import time

# Polars: lazy evaluation and query optimisation
# Reads only necessary columns, pushes filters to scan stage
query = (
    pl.scan_csv('large_events.csv')           # lazy — nothing runs yet
    .filter(pl.col('event_type') == 'purchase')
    .filter(pl.col('amount')     >  0)
    .group_by(['user_id', pl.col('date').str.slice(0, 7).alias('month')])
    .agg([
        pl.col('amount').sum().alias('total_spend'),
        pl.col('amount').count().alias('order_count'),
        pl.col('amount').mean().alias('avg_order'),
    ])
    .sort('total_spend', descending=True)
)

# Polars lazy plan is optimised before execution
print(query.explain())   # see the optimised plan

# Execute
start = time.perf_counter()
df    = query.collect()  # now it runs
print(f'Polars: {time.perf_counter() - start:.2f}s')
print(df.head())",
        ],
        'tips' => [
            'Use Polars\' lazy API (scan_csv → filter → group_by → collect) — it optimises the query plan before executing.',
            'Polars uses Apache Arrow memory format — it interops with pandas via df.to_pandas() without copying.',
            'dbt transforms raw SQL queries into a tested, documented, versioned data model — adopt it for any serious data pipeline.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert data science involves causal inference — understanding cause-and-effect relationships, not just correlations — using techniques like propensity score matching, difference-in-differences, and instrumental variables. Bayesian statistics (PyMC, Stan) enables probabilistic modelling with uncertainty quantification, which is more honest about what data can and cannot tell us. Contributing to data science as a discipline — open datasets, reproducible research, peer-reviewed publication — marks the expert practitioner.</p>',
        'concepts' => [
            'Causal inference: DAGs, confounders, instrumental variables, propensity score matching',
            'Difference-in-differences: natural experiments, parallel trends assumption',
            'Bayesian inference: prior, likelihood, posterior; MCMC sampling',
            'PyMC: model definition, NUTS sampler, posterior predictive checks',
            'Survival analysis: Kaplan-Meier, Cox PH model, censoring',
            'Experimental design: factorial designs, blocking, Latin squares',
            'Reproducible research: DVC, Kedro, data versioning, environment isolation',
        ],
        'code' => [
            'title'   => 'Bayesian A/B test with PyMC',
            'lang'    => 'python',
            'content' =>
"import pymc as pm
import numpy as np
import matplotlib.pyplot as plt

# Observed conversions
n_control,   conv_control   = 1000, 120  # 12%
n_treatment, conv_treatment = 1000, 145  # 14.5%

with pm.Model() as model:
    # Priors: weakly informative Beta distribution
    rate_control   = pm.Beta('rate_control',   alpha=1, beta=1)
    rate_treatment = pm.Beta('rate_treatment', alpha=1, beta=1)

    # Likelihood: Binomial observations
    obs_control   = pm.Binomial('obs_control',   n=n_control,   p=rate_control,   observed=conv_control)
    obs_treatment = pm.Binomial('obs_treatment', n=n_treatment, p=rate_treatment, observed=conv_treatment)

    # Derived quantity: relative lift
    lift = pm.Deterministic('lift', (rate_treatment - rate_control) / rate_control)

    trace = pm.sample(4000, tune=2000, return_inferencedata=True, progressbar=True)

# Probability that treatment is better
p_better = (trace.posterior['lift'] > 0).mean().item()
print(f'P(treatment better): {p_better:.3f}')
pm.plot_posterior(trace, var_names=['lift'], ref_val=0)",
        ],
        'tips' => [
            'Bayesian A/B tests give you P(treatment better) directly — more actionable than frequentist p-values.',
            'Always check the parallel trends assumption before applying difference-in-differences.',
            'DVC version-controls large datasets alongside code — essential for reproducible experiments.',
            'Read "Mostly Harmless Econometrics" by Angrist & Pischke for a rigorous causal inference foundation.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
