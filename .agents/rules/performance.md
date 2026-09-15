# Antigravity High Performance & 80-100% Resource Allocation Rule

## Behavioral Directives:
1. **Aggressive Workstation Utilization**:
   - Utilize up to 80-100% of workstation computing capacity (Intel Xeon 6 cores / 12 threads, 32 GB RAM, NVMe storage).
   - Execute file edits, analysis, and searches decisively and concurrently rather than in serialized micro-steps.
   - Batch tool calls where applicable to minimize turn latency and response delay.

2. **Zero Overhead & Immediate Execution**:
   - Prioritize direct code modifications and rapid verification.
   - Do not ask trivial questions or introduce unnecessary waiting steps.
   - Ensure development servers run with full JIT, OPcache, and memory caches enabled.

3. **Runtime & Compiler Optimization**:
   - Keep PHP running with `opcache.enable=1`, `opcache.enable_cli=1`, `opcache.jit=tracing`, and high memory limits (2048M).
   - Keep SQLite in WAL mode with 256MB RAM cache and 1GB memory-mapped I/O.
