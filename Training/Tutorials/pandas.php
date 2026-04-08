<?php
$tutorial_title = 'Pandas';
$tutorial_slug  = 'pandas';
$quiz_slug      = 'pandas';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>pandas is the Python data manipulation and analysis library. Built on top of NumPy, it provides two primary data structures — Series (1D labelled array) and DataFrame (2D labelled table) — along with a rich API for loading, cleaning, transforming, and analysing tabular data. pandas is the "Excel of Python" and the universal entry point for data work in Python.</p>',
        'concepts' => [
            'Series: 1D labelled array; index, dtype, name',
            'DataFrame: 2D table of Series; columns, index, dtypes',
            'Creating DataFrames: from dict, from CSV (read_csv), from SQL (read_sql)',
            'Selection: df["col"], df[["c1","c2"]], df.loc[row, col], df.iloc[i, j]',
            'Filtering: boolean indexing df[df["col"] > value]',
            'Basic operations: head(), tail(), shape, dtypes, describe(), info()',
            'Missing data: isnull(), fillna(), dropna()',
        ],
        'code' => [
            'title'   => 'pandas DataFrame essentials',
            'lang'    => 'python',
            'content' =>
"import pandas as pd
import numpy as np

# Create from dict
df = pd.DataFrame({
    'name':   ['Alice', 'Bob', 'Carol', 'Dave'],
    'score':  [92, 74, 88, None],
    'grade':  ['A', 'C', 'B', 'B'],
    'active': [True, False, True, True],
})

# Selection
print(df['name'])             # Series
print(df[['name', 'score']])  # DataFrame

# Label-based selection
print(df.loc[0:2, 'name':'score'])

# Boolean filtering
print(df[df['score'] > 80])
print(df[(df['active']) & (df['score'] > 70)])

# Missing values
print(df['score'].isnull().sum())          # 1
df['score'] = df['score'].fillna(df['score'].mean())
df.dropna(subset=['name'], inplace=True)

# Add computed column
df['pass'] = df['score'] >= 60
print(df)",
        ],
        'tips' => [
            'Use .loc[] for label-based and .iloc[] for integer-position-based indexing — never mix them.',
            'Avoid chained indexing (df["a"]["b"]) — it causes SettingWithCopyWarning; use df.loc[row, "b"].',
            'Use inplace=True sparingly — returning a new DataFrame is safer for chaining operations.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Group-by operations — the split-apply-combine paradigm — are the most powerful pandas feature for aggregating and transforming data. Understanding the difference between groupby().agg() (aggregate to scalar), groupby().transform() (preserve row count), and groupby().apply() (flexible function) enables most real-world data manipulation tasks.</p>',
        'concepts' => [
            'groupby(): split-apply-combine; groupby(col).agg({"c": "sum"})',
            'agg() vs. transform() vs. apply(): when to use each',
            'Pivot tables: pd.pivot_table() and df.pivot()',
            'Merging: pd.merge(how="inner/left/right/outer", on=, left_on=, right_on=)',
            'Concatenation: pd.concat([df1, df2], axis=0/1)',
            'String methods: df["col"].str.contains(), .lower(), .split(), .extract()',
            'apply(): row-wise and column-wise custom functions',
        ],
        'code' => [
            'title'   => 'GroupBy and merge',
            'lang'    => 'python',
            'content' =>
"import pandas as pd

orders    = pd.read_csv('orders.csv')
customers = pd.read_csv('customers.csv')

# GroupBy: stats per customer
stats = orders.groupby('customer_id').agg(
    order_count  = ('id',      'count'),
    total_spend  = ('amount',  'sum'),
    avg_spend    = ('amount',  'mean'),
    last_order   = ('date',    'max'),
).reset_index()

# Transform: add group total to every row (broadcast back to original shape)
orders['cust_total'] = orders.groupby('customer_id')['amount'].transform('sum')

# Merge with customer info (LEFT JOIN)
result = stats.merge(customers, on='customer_id', how='left')

# String operations
customers['first_name'] = customers['full_name'].str.split(' ').str[0]
customers['has_gmail']  = customers['email'].str.contains('@gmail\\.com', na=False)

# Pivot table: revenue by month and region
orders['month'] = pd.to_datetime(orders['date']).dt.to_period('M').astype(str)
pivot = orders.pivot_table(values='amount', index='month', columns='region', aggfunc='sum', fill_value=0)
print(pivot)",
        ],
        'tips' => [
            'Use .transform() instead of .apply() when you need the result aligned back to the original DataFrame.',
            'Add .reset_index() after groupby().agg() to turn the grouped index back into columns.',
            'Always specify how= in pd.merge() — the default "inner" is often not what you want.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Pandas performance matters when datasets grow beyond 10 million rows. Using correct dtypes (int32 instead of int64, float32 instead of float64, category for low-cardinality strings) can reduce memory usage by 50–80% and speed up operations dramatically. The eval() and query() methods use numexpr to evaluate expressions faster than pure Python. Understanding pandas\' execution model — why some operations create copies and others create views — prevents subtle bugs with unexpected mutation.</p>',
        'concepts' => [
            'Memory optimisation: astype(category), downcast numerics, pd.to_numeric(downcast=)',
            'Efficient reading: usecols, dtype dict, chunksize in read_csv',
            'df.eval() and df.query() for fast expression evaluation with numexpr',
            'Time series: DatetimeIndex, resample(), rolling(), expanding(), ewm()',
            'MultiIndex: pd.MultiIndex.from_tuples(), .xs(), .unstack(), .stack()',
            'Categorical dtype: Categorical, CategoricalDtype, pd.get_dummies() for ML',
            'Copy vs. view: when pandas creates a copy vs. a view of the data',
        ],
        'code' => [
            'title'   => 'Memory-efficient pandas',
            'lang'    => 'python',
            'content' =>
"import pandas as pd
import numpy as np

def optimise_dtypes(df: pd.DataFrame) -> pd.DataFrame:
    '''Reduce memory usage by downcasting numeric dtypes and using category.'''
    before = df.memory_usage(deep=True).sum() / 1e6

    for col in df.select_dtypes('float').columns:
        df[col] = pd.to_numeric(df[col], downcast='float')

    for col in df.select_dtypes('integer').columns:
        df[col] = pd.to_numeric(df[col], downcast='integer')

    for col in df.select_dtypes('object').columns:
        if df[col].nunique() / len(df) < 0.1:   # < 10% unique → category
            df[col] = df[col].astype('category')

    after = df.memory_usage(deep=True).sum() / 1e6
    print(f'Memory: {before:.1f} MB → {after:.1f} MB ({(1-after/before)*100:.0f}% reduction)')
    return df

# Read large CSV in chunks
chunks = []
for chunk in pd.read_csv('large.csv', chunksize=100_000):
    chunk = optimise_dtypes(chunk)
    chunk = chunk[chunk['active'] == 1]   # filter early
    chunks.append(chunk)
df = pd.concat(chunks, ignore_index=True)",
        ],
        'tips' => [
            'Convert low-cardinality string columns to category — it reduces memory by 5–10× and speeds up groupby.',
            'Use chunksize in read_csv to process files larger than RAM without loading everything at once.',
            'Profile with df.memory_usage(deep=True) before and after optimisation to measure improvement.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Pandas extension types — ArrowDtype, StringDtype, Int64Dtype (nullable integer), and BooleanDtype — provide better null-handling and interoperability with Arrow and Spark. The pandas 2.0 copy-on-write (COW) semantics change the performance model significantly: understanding when chained operations create copies vs. views is critical for both correctness and performance in pandas 2.x.</p>',
        'concepts' => [
            'Pandas 2.0 Copy-on-Write (COW): lazy copying, explicit chained assignment warning',
            'ArrowDtype: pd.ArrowDtype(pa.string()), 5–50× faster for string operations',
            'Nullable integer types: pd.Int64Dtype() vs. np.int64 — handles NA without float conversion',
            'Extension arrays: ExtensionArray, ExtensionDtype for custom data types',
            'Pandas and Polars interop: df.to_arrow(), pa.Table.to_pandas(), zero-copy conversion',
            'Pipe chaining: df.pipe(func) for method-chain compatible functions',
            'Custom accessors: @pd.api.extensions.register_dataframe_accessor',
        ],
        'code' => [
            'title'   => 'pandas with PyArrow backend',
            'lang'    => 'python',
            'content' =>
"import pandas as pd
import pyarrow as pa

# Use ArrowDtype for string-heavy DataFrames — much faster than object dtype
df = pd.read_csv('data.csv',
    dtype_backend='pyarrow',  # All columns use ArrowDtype
)

# Explicitly set ArrowDtype
df['name'] = df['name'].astype(pd.ArrowDtype(pa.string()))

# String operations on ArrowDtype are ~10× faster than object dtype
import time
t0 = time.perf_counter()
result = df['name'].str.lower().str.contains('alice')
print(f'ArrowDtype string op: {time.perf_counter()-t0:.4f}s')

# Custom DataFrame accessor
@pd.api.extensions.register_dataframe_accessor('money')
class MoneyAccessor:
    def __init__(self, df):
        self._df = df

    def format_currency(self, col: str, currency: str = 'USD') -> pd.Series:
        return self._df[col].map(lambda x: f'{currency} {x:,.2f}')

# Usage: df.money.format_currency('revenue', 'GBP')",
        ],
        'tips' => [
            'Use dtype_backend="pyarrow" in read_csv for large datasets — ArrowDtype is 5–50× faster for strings.',
            'In pandas 2.0+, use df = df.copy() explicitly when you want a copy — chained assignment now warns.',
            'Custom accessors (register_dataframe_accessor) keep domain logic close to the data without subclassing.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert pandas involves contributing to the pandas project, understanding the internals of the BlockManager (how DataFrame stores data in numpy arrays under the hood) and its replacement in pandas 2.x with the new ArrowExtensionArray-based storage, and building pandas-compatible extension types for domain-specific data (geospatial, genomics, finance). The pandas developer community and the pandas Enhancement Proposals (PDEP) shape the library\'s future direction.</p>',
        'concepts' => [
            'pandas internals: BlockManager, consolidation, copy-on-write refcount',
            'Writing ExtensionArray subclasses: _dtype, _from_sequence, _validate, arithmetic',
            'PDEP (pandas Enhancement Proposal): proposal process, discussion on GitHub',
            'pandas testing: pd.testing.assert_frame_equal, pd.testing.assert_series_equal',
            'Benchmarking pandas: using ASV (airspeed velocity) for performance regression testing',
            'Contributing to pandas: issues, good first issues, PR process, CI/CD on GitHub',
            'pandas and the broader data ecosystem: Arrow, Polars, DuckDB, Ibis',
        ],
        'code' => [
            'title'   => 'Custom ExtensionArray for domain data',
            'lang'    => 'python',
            'content' =>
"import pandas as pd
import numpy as np
from pandas.api.extensions import ExtensionArray, ExtensionDtype

class MoneyDtype(ExtensionDtype):
    name    = 'money'
    type    = float
    kind    = 'f'

    @classmethod
    def construct_array_type(cls):
        return MoneyArray

class MoneyArray(ExtensionArray):
    dtype = MoneyDtype()

    def __init__(self, values):
        self._data = np.asarray(values, dtype='float64')

    @classmethod
    def _from_sequence(cls, scalars, dtype=None, copy=False):
        return cls(scalars)

    def __len__(self): return len(self._data)
    def __getitem__(self, key): return self._data[key]

    def format(self, currency='USD'):
        return [f'{currency} {v:,.2f}' for v in self._data]

# Register and use
pd.api.extensions.register_extension_dtype(MoneyDtype)
s = pd.array([100.5, 2000.75, 50.0], dtype=MoneyDtype())
print(MoneyArray(s).format('GBP'))",
        ],
        'tips' => [
            'Read pandas source code before writing an ExtensionArray — the existing NumpyExtensionArray is the best guide.',
            'Submit bugs with a minimal reproducible example — pd.testing utilities make this straightforward.',
            'Follow the pandas-dev mailing list and GitHub discussions for upcoming API changes in next releases.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
