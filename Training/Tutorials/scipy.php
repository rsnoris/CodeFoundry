<?php
$tutorial_title = 'SciPy';
$tutorial_slug  = 'scipy';
$quiz_slug      = 'scipy';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>SciPy (Scientific Python) is an open-source library built on NumPy that provides a vast collection of algorithms for scientific and technical computing. It covers numerical integration, optimisation, interpolation, signal processing, linear algebra, statistics, spatial data, and more. Together with NumPy, pandas, and matplotlib, SciPy forms the core of the Python scientific computing ecosystem, providing the tools that engineers, physicists, biologists, and economists use daily.</p>',
        'concepts' => [
            'SciPy submodules: stats, optimize, integrate, interpolate, linalg, signal, spatial, ndimage, sparse',
            'scipy.stats: descriptive stats, probability distributions, hypothesis tests',
            'Probability distributions: norm, t, chi2, f, binom, poisson, uniform, expon',
            'scipy.linalg vs. numpy.linalg: SciPy provides more routines and can use LAPACK directly',
            'Physical constants: scipy.constants (speed of light, Boltzmann constant, etc.)',
            'Special functions: scipy.special (gamma, beta, erf, bessel, etc.)',
            'Sparse matrices: scipy.sparse for memory-efficient large sparse systems',
        ],
        'code' => [
            'title'   => 'SciPy statistical distributions',
            'lang'    => 'python',
            'content' =>
"from scipy import stats
import numpy as np
import matplotlib.pyplot as plt

# Fit a normal distribution to data
data = np.concatenate([np.random.normal(0, 1, 500), np.random.normal(3, 1.5, 300)])
mu, sigma = stats.norm.fit(data)
print(f'Fitted: μ={mu:.2f}, σ={sigma:.2f}')

# Probability computations
dist = stats.norm(mu, sigma)
print(f'P(X < 2):    {dist.cdf(2):.4f}')
print(f'P(X > 2):    {dist.sf(2):.4f}')   # survival function = 1 - cdf
print(f'95th pctile: {dist.ppf(0.95):.4f}')

# Plot: fitted distribution over histogram
x = np.linspace(data.min(), data.max(), 200)
plt.hist(data, bins=40, density=True, alpha=0.5, label='Data')
plt.plot(x, dist.pdf(x), 'r-', lw=2, label=f'Normal({mu:.2f}, {sigma:.2f})')
plt.legend(); plt.show()

# Normality test
stat, p = stats.shapiro(data[:50])  # Shapiro-Wilk (best for small n)
print(f'Shapiro-Wilk: W={stat:.4f}, p={p:.4f}')",
        ],
        'tips' => [
            'scipy.stats distributions have consistent API: pdf, cdf, ppf, rvs, fit — learn one, know them all.',
            'Use scipy.stats.norm.ppf() (percent point function) for quantile calculations — it is the inverse of cdf.',
            'For large samples (n > 5000), the Shapiro-Wilk test is too sensitive — use the KS test instead.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>SciPy\'s optimisation module (<code>scipy.optimize</code>) solves minimisation problems, root finding, and curve fitting — the mathematical backbone of ML, control systems, and scientific modelling. <code>minimize()</code> with gradient-based methods (L-BFGS-B, BFGS, Nelder-Mead) and constraint handling gives you a general-purpose optimiser. <code>curve_fit()</code> fits arbitrary functions to data with uncertainty estimates.</p>',
        'concepts' => [
            'scipy.optimize.minimize(): objective function, gradient, bounds, constraints',
            'Minimisation methods: Nelder-Mead (derivative-free), L-BFGS-B (gradient-based)',
            'scipy.optimize.curve_fit(): non-linear least squares, returns popt and pcov',
            'scipy.optimize.root(): solving systems of equations f(x) = 0',
            'scipy.optimize.brentq(): scalar root finding on an interval',
            'scipy.optimize.linprog(): linear programming (minimise cᵀx subject to Ax ≤ b)',
            'scipy.optimize.differential_evolution(): global optimisation with DE',
        ],
        'code' => [
            'title'   => 'Curve fitting and minimisation',
            'lang'    => 'python',
            'content' =>
"from scipy.optimize import curve_fit, minimize
import numpy as np
import matplotlib.pyplot as plt

# Curve fitting: fit an exponential decay model
def decay_model(t, A, tau, C):
    return A * np.exp(-t / tau) + C

t_data = np.linspace(0, 10, 50)
y_true = decay_model(t_data, A=5, tau=2, C=1)
y_noisy = y_true + 0.3 * np.random.randn(len(t_data))

# Fit — returns optimal params and covariance matrix
popt, pcov = curve_fit(
    decay_model, t_data, y_noisy,
    p0=[4, 3, 0],         # initial guess
    bounds=([0, 0, 0], [np.inf, np.inf, np.inf]),
)
perr = np.sqrt(np.diag(pcov))  # 1-sigma uncertainties
print(f'A={popt[0]:.2f}±{perr[0]:.2f}, tau={popt[1]:.2f}±{perr[1]:.2f}')

# Minimisation with bounds
result = minimize(
    fun=lambda x: (x[0]-3)**2 + (x[1]+1)**2 + 0.5*x[0]*x[1],
    x0=[0, 0],
    method='L-BFGS-B',
    bounds=[(-5, 5), (-5, 5)],
)
print(f'Minimum at: {result.x}, f={result.fun:.4f}')",
        ],
        'tips' => [
            'Always provide a good initial guess (p0) to curve_fit — bad initial values cause convergence failures.',
            'pcov diagonal gives variance; take sqrt for standard error of each parameter.',
            'Use L-BFGS-B for bounded minimisation with gradient information; Nelder-Mead when gradients are unavailable.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>SciPy\'s integration module (<code>scipy.integrate</code>) solves ODEs (ordinary differential equations) and computes definite integrals. <code>solve_ivp()</code> with Runge-Kutta methods simulates physical systems — from pendulums to population dynamics to circuit models. Numerical integration with <code>quad()</code>, <code>dblquad()</code>, and <code>tplquad()</code> handles smooth mathematical functions. Signal processing with <code>scipy.signal</code> provides filtering, convolution, and spectral analysis.</p>',
        'concepts' => [
            'scipy.integrate.solve_ivp(): IVP (initial value problem) solvers (RK45, RK23, DOP853)',
            'ODE event functions: terminal events, direction, event detection',
            'scipy.integrate.quad(): adaptive Gaussian quadrature for definite integrals',
            'scipy.signal: butterworth/chebyshev filters, sosfilt, welch PSD, spectrogram',
            'scipy.interpolate: interp1d, CubicSpline, RBFInterpolant',
            'scipy.spatial: KDTree for nearest-neighbour search, ConvexHull, Delaunay',
            'scipy.ndimage: N-dimensional image processing (filters, morphology, measurements)',
        ],
        'code' => [
            'title'   => 'ODE solving — Lotka-Volterra predator-prey',
            'lang'    => 'python',
            'content' =>
"from scipy.integrate import solve_ivp
import numpy as np
import matplotlib.pyplot as plt

# Lotka-Volterra predator-prey model
# dx/dt = αx − βxy  (prey:  reproduce − get eaten)
# dy/dt = δxy − γy  (predator: eat prey − die)

def lotka_volterra(t, state, alpha, beta, delta, gamma):
    x, y = state
    return [
        alpha * x - beta  * x * y,   # dx/dt
        delta * x * y - gamma * y,   # dy/dt
    ]

sol = solve_ivp(
    fun=lotka_volterra,
    t_span=(0, 50),
    y0=[10, 5],                   # initial: 10 prey, 5 predators
    args=(1.0, 0.1, 0.075, 1.5),  # α, β, δ, γ
    method='RK45',
    t_eval=np.linspace(0, 50, 1000),
    rtol=1e-8,
)

plt.figure(figsize=(12, 4))
plt.subplot(1, 2, 1)
plt.plot(sol.t, sol.y[0], label='Prey'); plt.plot(sol.t, sol.y[1], label='Predator')
plt.xlabel('Time'); plt.legend()
plt.subplot(1, 2, 2)
plt.plot(sol.y[0], sol.y[1]); plt.xlabel('Prey'); plt.ylabel('Predator')
plt.tight_layout(); plt.show()",
        ],
        'tips' => [
            'Use method="RK45" for most ODEs; "DOP853" for high-accuracy requirements; "Radau" for stiff systems.',
            'Set rtol and atol in solve_ivp — the defaults may be too loose for some physical simulations.',
            'Use scipy.spatial.KDTree for nearest-neighbour queries — it is O(log n) vs. O(n) brute force.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>SciPy\'s sparse matrix module enables large-scale linear systems — the kind that arise in finite element analysis, graph algorithms, and network flow — where dense matrix storage would be prohibitive. Direct solvers (spsolve) and iterative solvers (gmres, cg, lgmres) with preconditioners handle systems with millions of unknowns. SciPy\'s sparse eigenvalue solver (eigsh, eigs) via ARPACK is the standard tool for dimensionality reduction (spectral methods, PCA on large sparse matrices).</p>',
        'concepts' => [
            'scipy.sparse: csr_matrix, csc_matrix, coo_matrix, lil_matrix; format tradeoffs',
            'Sparse linear solvers: spsolve (direct), gmres, cg, minres (iterative)',
            'Sparse eigenvalue problems: eigsh (symmetric), eigs (general), power method',
            'Graph algorithms via sparse: scipy.sparse.csgraph — Dijkstra, connected_components, min_spanning_tree',
            'scipy.stats.gaussian_kde: kernel density estimation',
            'scipy.stats.copula: multivariate dependence modelling',
            'Resampling methods: scipy.stats.bootstrap, permutation_test',
        ],
        'code' => [
            'title'   => 'Sparse linear system solver',
            'lang'    => 'python',
            'content' =>
"from scipy.sparse        import diags, eye, kron
from scipy.sparse.linalg import spsolve, LinearOperator, gmres
import numpy as np

# Poisson equation on a 2D grid using finite differences
# −∇²u = f,  boundary conditions u = 0
n = 50  # n×n grid
h = 1.0 / (n + 1)

# 1D second-difference operator
T = diags([-1, 2, -1], [-1, 0, 1], shape=(n, n)) / h**2

# 2D operator via Kronecker product
I  = eye(n)
L  = kron(T, I) + kron(I, T)   # shape: (n², n²)

print(f'System size: {L.shape}, nnz: {L.nnz}, density: {L.nnz/L.shape[0]**2:.5f}')

# Right-hand side: f(x,y) = sin(πx)sin(πy)
x = y = np.linspace(h, 1-h, n)
X, Y = np.meshgrid(x, y)
f = np.sin(np.pi * X) * np.sin(np.pi * Y)

# Direct solve — exact solution
u = spsolve(L.tocsr(), f.ravel())
print('Max error vs. analytical:', np.max(np.abs(u - f.ravel() / (2*np.pi**2))))",
        ],
        'tips' => [
            'Use CSR format (csr_matrix) for row operations and matrix-vector products; CSC for column operations.',
            'Direct solvers (spsolve) are memory-intensive for very large systems — use iterative solvers (gmres, cg) for n > 100k.',
            'scipy.sparse.csgraph.shortest_path() is fast Dijkstra on sparse adjacency matrices.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert SciPy involves Cython and C extensions to speed up tight scientific loops, contributing to the SciPy project (which follows a rigorous review process for numerical accuracy), and understanding the BLAS/LAPACK/ARPACK/FFTPACK backends that power SciPy\'s routines. Domain-specific applications — finite element analysis (FEniCS), fluid dynamics (OpenFOAM Python interface), molecular dynamics (MDAnalysis), and astronomy (Astropy) — use SciPy as their computational backbone.</p>',
        'concepts' => [
            'Writing Cython extensions for SciPy bottlenecks: typed memoryviews, C arrays',
            'LAPACK and BLAS direct access: scipy.linalg.get_blas_funcs(), get_lapack_funcs()',
            'scipy.fft vs. numpy.fft: scipy provides more routines (DCT, DST, Hartley)',
            'SciPy proposal process: github.com/scipy/scipy; SciPy roadmap',
            'Domain applications: Astropy (astronomy), MDAnalysis (biophysics), FEniCS (FEM)',
            'Benchmarking SciPy: airspeed velocity (asv) for regression detection',
            'Numerical precision: ULP (Units in the Last Place), catastrophic cancellation avoidance',
        ],
        'code' => [
            'title'   => 'LAPACK direct access for speed',
            'lang'    => 'python',
            'content' =>
"import numpy as np
from scipy.linalg import get_lapack_funcs, get_blas_funcs

# Get direct access to optimised LAPACK/BLAS routines
# Bypasses Python overhead for performance-critical inner loops
A = np.random.rand(500, 500).astype(np.float64)
b = np.random.rand(500).astype(np.float64)

# LAPACK gesv: general linear system solver (LU factorisation)
(gesv,) = get_lapack_funcs(('gesv',), (A, b))
lu, piv, x, info = gesv(A, b[:, None])
print('Residual:', np.max(np.abs(A @ x.ravel() - b)))

# BLAS dgemm: double-precision matrix multiply
dgemm = get_blas_funcs('gemm', (A, A))
C = dgemm(1.0, A, A)   # C = A @ A, BLAS-level speed

# For timing comparison
import time
t0 = time.perf_counter(); _ = A @ A
print(f'numpy @ : {time.perf_counter()-t0:.4f}s')
t0 = time.perf_counter(); _ = dgemm(1.0, A, A)
print(f'BLAS    : {time.perf_counter()-t0:.4f}s')",
        ],
        'tips' => [
            'Direct BLAS access is beneficial only when function call overhead is significant (small matrices in tight loops).',
            'Use scipy.linalg over numpy.linalg — it has a wider routine selection and can outperform for specific operations.',
            'Read the SciPy development guide (scipy.github.io/devdocs) before contributing — numerical accuracy is paramount.',
            'Follow the SciPy mailing list and GitHub discussions for roadmap items like JAX/PyTorch integration.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
