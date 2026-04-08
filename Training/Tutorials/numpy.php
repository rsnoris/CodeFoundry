<?php
$tutorial_title = 'NumPy';
$tutorial_slug  = 'numpy';
$quiz_slug      = 'numpy';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>NumPy (Numerical Python) is the foundation of the entire Python scientific computing ecosystem. It provides the ndarray — an N-dimensional array object that stores homogeneous data contiguously in memory — along with a library of mathematical functions to operate on these arrays efficiently. NumPy operations are implemented in C and run 10–100× faster than equivalent pure Python loops. Understanding NumPy is a prerequisite for pandas, scikit-learn, PyTorch, and TensorFlow.</p>',
        'concepts' => [
            'ndarray: shape, dtype, ndim, size, itemsize, strides',
            'Creating arrays: np.array(), np.zeros(), np.ones(), np.arange(), np.linspace()',
            'Data types: float64, float32, int64, int32, bool, complex128',
            'Basic operations: arithmetic (+, -, *, /), broadcasting rules',
            'Indexing and slicing: 1D, 2D, negative indexing, step slicing',
            'Aggregations: sum(), mean(), std(), min(), max(), argmin(), argmax()',
            'Random module: np.random.seed(), rand(), randn(), randint(), choice()',
        ],
        'code' => [
            'title'   => 'NumPy array basics',
            'lang'    => 'python',
            'content' =>
"import numpy as np

# Create arrays
a = np.array([1, 2, 3, 4, 5], dtype=np.float64)
b = np.arange(0, 10, 2)            # [0, 2, 4, 6, 8]
c = np.linspace(0, 1, 5)           # [0, 0.25, 0.5, 0.75, 1.0]
m = np.zeros((3, 4), dtype=int)    # 3×4 matrix of zeros
I = np.eye(3)                       # 3×3 identity matrix

# Vectorised arithmetic — no Python loop needed
x = np.array([1.0, 4.0, 9.0, 16.0])
print(np.sqrt(x))                   # [1. 2. 3. 4.]
print(x ** 0.5)                     # same thing
print(x + 100)                      # broadcast: [101. 104. 109. 116.]

# 2D array and slicing
mat = np.arange(12).reshape(3, 4)
print(mat[:, 1])   # second column: [1, 5, 9]
print(mat[1, :])   # second row:    [4, 5, 6, 7]
print(mat[::2])    # every other row

# Aggregations
print(mat.sum(axis=0))  # column sums: [12, 15, 18, 21]
print(mat.sum(axis=1))  # row sums:    [ 6, 22, 38]",
        ],
        'tips' => [
            'Use dtype=np.float32 instead of float64 for ML arrays — it halves memory and speeds up GPU operations.',
            'Never use Python for-loops over NumPy arrays — use vectorised operations or np.vectorize().',
            'Check the shape of arrays frequently — shape mismatches are the most common NumPy bug.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Broadcasting is NumPy\'s mechanism for applying operations between arrays of different shapes — it automatically expands dimensions to make the shapes compatible, enabling element-wise operations without explicitly copying data. Boolean indexing (fancy indexing) enables powerful, readable data filtering. NumPy\'s linear algebra module (np.linalg) provides matrix operations used throughout data science and ML.</p>',
        'concepts' => [
            'Broadcasting rules: dimensions must be equal or one of them must be 1',
            'Boolean indexing: condition arrays, np.where(), np.nonzero()',
            'Fancy indexing: integer array indices for arbitrary element selection',
            'np.concatenate, np.stack, np.hstack, np.vstack for combining arrays',
            'np.split, np.hsplit, np.vsplit for dividing arrays',
            'Linear algebra: np.dot(), @, np.linalg.inv(), np.linalg.det(), np.linalg.eig()',
            'np.sort, np.argsort, np.unique, np.searchsorted',
        ],
        'code' => [
            'title'   => 'Broadcasting and boolean indexing',
            'lang'    => 'python',
            'content' =>
"import numpy as np

# Broadcasting: normalise each row independently
data = np.array([[1., 2., 3.], [4., 5., 6.], [7., 8., 9.]])
row_means = data.mean(axis=1, keepdims=True)  # shape (3, 1)
row_stds  = data.std(axis=1,  keepdims=True)  # shape (3, 1)
normalised = (data - row_means) / row_stds    # broadcast: (3,3) - (3,1) → (3,3)

# Boolean indexing
x = np.array([3, -2, 7, -1, 5, -8, 4])
positive = x[x > 0]          # [3, 7, 5, 4]
x[x < 0] = 0                  # replace negatives with 0 in-place

# np.where: conditional element selection
signs = np.where(x > 0, 'pos', 'neg')

# Matrix operations
A = np.array([[1, 2], [3, 4]], dtype=float)
b = np.array([5, 6], dtype=float)

x = np.linalg.solve(A, b)     # solve Ax = b
print(x)                       # [−4.  4.5]
print(np.allclose(A @ x, b))  # True — verify solution",
        ],
        'tips' => [
            'Use keepdims=True in aggregations to preserve shape for broadcasting — it avoids many reshape() calls.',
            'np.linalg.solve() is more numerically stable than np.linalg.inv() @ b — prefer it for solving linear systems.',
            'Boolean indexing returns a copy; use np.where() for conditional assignment to avoid this pitfall.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>NumPy\'s memory model — C-contiguous (row-major) vs. Fortran-contiguous (column-major) order, strides, and views vs. copies — is crucial for writing high-performance numerical code. Vectorising functions with np.vectorize, np.frompyfunc, or Numba\'s @njit decorator transforms Python functions into fast, array-aware operations. Structured arrays (dtypes with field names) handle heterogeneous tabular data without pandas overhead.</p>',
        'concepts' => [
            'Memory layout: C-order vs. F-order, strides, contiguous arrays',
            'Views vs. copies: when slicing returns a view; ndarray.base, np.shares_memory()',
            'np.vectorize and np.frompyfunc for element-wise Python functions',
            'Numba: @njit, @vectorize, @guvectorize for JIT-compiled NumPy functions',
            'Structured arrays: dtype with field names for tabular data',
            'Masked arrays: np.ma for arrays with missing/invalid values',
            'np.einsum for arbitrary tensor contractions',
        ],
        'code' => [
            'title'   => 'Numba JIT for performance',
            'lang'    => 'python',
            'content' =>
"import numpy as np
from numba import njit, prange
import time

# Pure Python: O(n) but slow due to interpreter overhead
def mandelbrot_py(c, max_iter=100):
    z = 0
    for i in range(max_iter):
        z = z*z + c
        if abs(z) > 2:
            return i
    return max_iter

# Numba JIT: same code, ~100× faster
@njit(parallel=True, cache=True)
def mandelbrot_numba(height: int, width: int, max_iter: int = 100):
    result = np.zeros((height, width), dtype=np.int32)
    for y in prange(height):
        for x in range(width):
            c  = complex(-2 + 3*x/width, -1.5 + 3*y/height)
            z  = 0j
            for i in range(max_iter):
                z = z*z + c
                if z.real*z.real + z.imag*z.imag > 4:
                    result[y, x] = i
                    break
            else:
                result[y, x] = max_iter
    return result",
        ],
        'tips' => [
            'Numba\'s @njit(cache=True) avoids recompilation on every restart — essential for interactive use.',
            'Use np.einsum for matrix multiplications and tensor contractions — it is readable and often faster.',
            'Prefer in-place operations (+=, *=) on large arrays to avoid creating temporary copies.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>FFT (Fast Fourier Transform) with np.fft enables signal processing, frequency analysis, and convolution operations. Polynomial fitting with np.polyfit, interpolation, and numerical integration bridge the gap between discrete data and continuous mathematics. The numpy.random Generator API (vs. legacy np.random functions) provides reproducible, seedable random number generation with access to 40+ distributions.</p>',
        'concepts' => [
            'np.fft: fft, ifft, fftfreq, rfft for real-valued signals, fftshift',
            'np.polyfit and np.poly1d: polynomial regression and evaluation',
            'scipy integration: np.trapz, scipy.integrate.quad, cumulative_trapezoid',
            'numpy.random Generator: default_rng(), choice(), permutation, distributions',
            'Sparse matrices: scipy.sparse for memory-efficient large sparse arrays',
            'np.gradient: numerical differentiation for gradients along array dimensions',
            'np.histogram, np.histogram2d, np.histogramdd for multi-dimensional histograms',
        ],
        'code' => [
            'title'   => 'FFT for signal frequency analysis',
            'lang'    => 'python',
            'content' =>
"import numpy as np
import matplotlib.pyplot as plt

# Generate synthetic signal: 50 Hz + 120 Hz + noise
fs    = 1000          # sampling frequency (Hz)
t     = np.arange(0, 1, 1/fs)
sig   = (np.sin(2 * np.pi * 50  * t)    # 50 Hz component
       + np.sin(2 * np.pi * 120 * t)    # 120 Hz component
       + 0.5 * np.random.randn(len(t))) # Gaussian noise

# FFT
freqs    = np.fft.rfftfreq(len(sig), d=1/fs)   # positive frequencies only
spectrum = np.abs(np.fft.rfft(sig))             # magnitude spectrum

# Plot
fig, (ax1, ax2) = plt.subplots(2, 1, figsize=(10, 6))
ax1.plot(t[:200], sig[:200]); ax1.set_xlabel('Time (s)')
ax2.plot(freqs, spectrum);     ax2.set_xlabel('Frequency (Hz)')
ax2.set_xlim(0, 200)           # zoom to 0-200 Hz
plt.tight_layout(); plt.show()

# Identify dominant frequencies
top_freqs = freqs[np.argsort(spectrum)[-5:]]
print('Top 5 frequencies:', sorted(top_freqs))",
        ],
        'tips' => [
            'Use np.fft.rfft() for real-valued signals — it is 2× faster and returns only positive frequencies.',
            'Always apply a window function (Hanning, Blackman) before FFT to reduce spectral leakage.',
            'np.random.default_rng(seed) is the modern API — it is thread-safe and has better statistical properties.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert NumPy involves understanding how NumPy\'s Universal Functions (ufuncs) work — including writing custom ufuncs with np.frompyfunc or the C-API — and how the memory model interacts with BLAS/LAPACK for optimal matrix operation performance. Contributing to the NumPy project, participating in NEP (NumPy Enhancement Proposal) discussions, and understanding the Array Protocol (the __array__ and __array_ufunc__ interfaces that make NumPy extensible to GPU arrays, sparse arrays, and domain-specific arrays) marks the NumPy expert.</p>',
        'concepts' => [
            'Universal functions (ufuncs): np.frompyfunc, ufunc.reduce, ufunc.outer, ufunc.at',
            'BLAS/LAPACK: how np.dot() and np.linalg dispatch to optimised CPU routines',
            'The __array_ufunc__ protocol: overriding ufunc behaviour for custom array types',
            'The __array_function__ protocol: dispatching array functions to custom backends',
            'CuPy: drop-in GPU NumPy replacement; cupy.ndarray, cupy.get_array_module()',
            'Array API standard (array-api): vendor-neutral array computing protocol',
            'NEP (NumPy Enhancement Proposal) process and contributing to NumPy core',
        ],
        'code' => [
            'title'   => 'GPU NumPy with CuPy',
            'lang'    => 'python',
            'content' =>
"# CuPy: GPU-accelerated NumPy replacement
# pip install cupy-cuda12x  (match your CUDA version)
import cupy as cp
import numpy as np
import time

# Write array-library-agnostic code using get_array_module
def compute(arr):
    xp = cp.get_array_module(arr)   # returns np or cp
    return xp.sum(xp.exp(-arr**2))  # same code, different backend

# CPU
a_cpu = np.random.randn(10_000_000)
t0 = time.perf_counter()
r_cpu = compute(a_cpu)
print(f'NumPy (CPU): {time.perf_counter()-t0:.3f}s — result={r_cpu:.4f}')

# GPU
a_gpu = cp.asarray(a_cpu)          # copy to GPU once
t0 = time.perf_counter()
r_gpu = compute(a_gpu)
cp.cuda.Stream.null.synchronize()  # wait for GPU to finish
print(f'CuPy  (GPU): {time.perf_counter()-t0:.3f}s — result={float(r_gpu):.4f}')",
        ],
        'tips' => [
            'Use cp.get_array_module() to write library-agnostic code that runs on both CPU (NumPy) and GPU (CuPy).',
            'Minimise CPU↔GPU transfers — they are slow. Do as much computation on the GPU as possible.',
            'Follow the NumPy GitHub discussions and NEPs to understand the direction of array computing in Python.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
