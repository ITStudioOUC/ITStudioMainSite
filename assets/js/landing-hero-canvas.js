(() => {
  const hero = document.querySelector('.landing-hero');
  const canvas = document.querySelector('.landing-hero-canvas');
  if (!hero || !canvas) return;

  const ctx = canvas.getContext('2d');
  if (!ctx) return;

  const reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

  let width = 0;
  let height = 0;
  let dpr = 1;
  let rafId = null;
  let startAt = performance.now();

  const EMPTY_SET = new Set();
  const WORD_RE = /^[A-Za-z_][A-Za-z0-9_]*$/;
  const NUMBER_RE = /^\d+(?:_\d+)*(?:\.\d+)?$/;
  const STRING_RE = /^("(?:\\.|[^"\\])*"|'(?:\\.|[^'\\])*'|`(?:\\.|[^`\\])*`)$/;
  const CONSTANT_RE = /^[A-Z][A-Z0-9_]{2,}$/;
  const TYPE_RE = /^[A-Z][A-Za-z0-9_]*$/;
  const OPERATOR_RE = /^(::|->|=>|==|!=|<=|>=|\+=|-=|\*=|\/=|&&|\|\||[+\-*/%=&|^!<>?:.#])$/;
  const TOKEN_RE = /(\/\/.*$|#\[.*$|"(?:\\.|[^"\\])*"|'(?:\\.|[^'\\])*'|`(?:\\.|[^`\\])*`|\b\d+(?:_\d+)*(?:\.\d+)?\b|[A-Za-z_][A-Za-z0-9_]*|::|->|=>|==|!=|<=|>=|\+=|-=|\*=|\/=|&&|\|\||[+\-*/%=&|^!<>?:.,;()[\]{}#])/g;

  const LANGUAGE_KEYWORDS = {
    springboot: new Set([
      'package', 'import', 'public', 'private', 'protected', 'class', 'interface',
      'static', 'final', 'new', 'return', 'if', 'else', 'try', 'catch', 'finally',
      'throw', 'throws', 'void', 'long', 'int', 'boolean', 'null', 'true', 'false',
      'extends', 'implements', 'this',
    ]),
    rust: new Set([
      'use', 'pub', 'struct', 'enum', 'impl', 'async', 'await', 'fn', 'let', 'mut',
      'loop', 'match', 'if', 'else', 'return', 'while', 'for', 'in', 'break', 'continue',
      'crate', 'self', 'super', 'mod', 'where', 'Result', 'Ok', 'Err',
    ]),
    cpp: new Set([
      'include', 'template', 'typename', 'concept', 'requires', 'struct', 'class',
      'public', 'private', 'protected', 'explicit', 'void', 'auto', 'const', 'constexpr',
      'if', 'else', 'while', 'for', 'return', 'using', 'namespace', 'std', 'nullptr',
      'true', 'false',
    ]),
    python: new Set([
      'import', 'from', 'as', 'class', 'def', 'return', 'if', 'elif', 'else', 'for',
      'while', 'in', 'and', 'or', 'not', 'await', 'async', 'with', 'try', 'except',
      'finally', 'lambda', 'None', 'True', 'False', 'yield',
    ]),
    javascript: new Set([
      'class', 'constructor', 'return', 'if', 'else', 'while', 'for', 'const', 'let',
      'var', 'new', 'async', 'await', 'try', 'catch', 'finally', 'throw', 'export',
      'import', 'from', 'this', 'null', 'true', 'false', 'yield', 'function',
    ]),
    nim: new Set([
      'import', 'type', 'enum', 'object', 'ref', 'proc', 'result', 'let', 'var',
      'if', 'elif', 'else', 'for', 'in', 'while', 'return', 'await', 'defer',
      'mod', 'echo', 'discard',
    ]),
  };

  const classifyToken = (token, language, nextToken, prevToken, atLineStart) => {
    const keywords = LANGUAGE_KEYWORDS[language] || EMPTY_SET;

    if (token.startsWith('//')) return 'comment';
    if (token.startsWith('#[') || token.startsWith('@')) return 'annotation';
    if (STRING_RE.test(token)) return 'string';
    if (NUMBER_RE.test(token)) return 'number';
    if (OPERATOR_RE.test(token)) return 'operator';

    if (!WORD_RE.test(token)) {
      return 'plain';
    }

    if (keywords.has(token)) return 'keyword';
    if (language === 'cpp' && atLineStart && prevToken === '#' && token === 'include') return 'keyword';
    if (CONSTANT_RE.test(token)) return 'constant';
    if (nextToken === '(') return 'method';
    if (TYPE_RE.test(token)) return 'type';
    return 'field';
  };

  const tokenizeLine = (line, language) => {
    if (line.length === 0) return [['', 'plain']];

    const matches = [...line.matchAll(TOKEN_RE)];
    if (matches.length === 0) return [[line, 'plain']];

    const tokens = [];
    let cursor = 0;

    for (let i = 0; i < matches.length; i += 1) {
      const match = matches[i];
      const start = match.index ?? 0;
      const raw = match[0];
      const nextToken = i + 1 < matches.length ? matches[i + 1][0] : '';
      const prevToken = i > 0 ? matches[i - 1][0] : '';
      const atLineStart = line.slice(0, start).trim().length === 0;

      if (start > cursor) {
        tokens.push([line.slice(cursor, start), 'plain']);
      }

      if ((language === 'python' || language === 'nim') && raw === '#') {
        tokens.push([line.slice(start), 'comment']);
        cursor = line.length;
        break;
      }

      tokens.push([raw, classifyToken(raw, language, nextToken, prevToken, atLineStart)]);
      cursor = start + raw.length;
    }

    if (cursor < line.length) {
      tokens.push([line.slice(cursor), 'plain']);
    }

    if (tokens.length === 0) {
      tokens.push(['', 'plain']);
    }
    return tokens;
  };

  const tokenizeSnippet = (lines, language) => lines.map((line) => tokenizeLine(line, language));

  const rawSnippetCatalog = [
    {
      name: 'springboot',
      repeat: 4,
      lines: [
        'package cn.edu.ouc.itstudio.payment.workflow;',
        '',
        'import org.springframework.stereotype.Service;',
        'import org.springframework.transaction.annotation.Transactional;',
        'import org.springframework.transaction.annotation.Propagation;',
        'import org.springframework.transaction.annotation.Isolation;',
        'import org.springframework.retry.support.RetryTemplate;',
        'import java.math.BigDecimal;',
        'import java.time.Duration;',
        'import java.util.Optional;',
        'import java.util.concurrent.CompletableFuture;',
        'import java.util.concurrent.ConcurrentHashMap;',
        'import java.util.concurrent.ConcurrentMap;',
        'import java.util.concurrent.atomic.AtomicLong;',
        '',
        '@Service',
        '@RequiredArgsConstructor',
        'public class PaymentWorkflowOrchestrator {',
        '    private static final String IDEMPOTENCY_PREFIX = "pay:idem:";',
        '    private static final BigDecimal RISK_THRESHOLD = new BigDecimal("9999.99");',
        '    private static final Duration LOCK_TTL = Duration.ofSeconds(45);',
        '',
        '    private final PaymentRepository paymentRepository;',
        '    private final RiskEngine riskEngine;',
        '    private final RetryTemplate retryTemplate;',
        '    private final DistributedLockManager lockManager;',
        '    private final IdempotencyGateway idempotencyGateway;',
        '    private final OutboxPublisher outboxPublisher;',
        '    private final CompensationService compensator;',
        '    private final GatewayClient gateway;',
        '    private final Clock clock;',
        '    private final TraceContext traceContext;',
        '    private final ConcurrentMap<String, AtomicLong> inMemorySeq = new ConcurrentHashMap<>();',
        '',
        '    @Transactional(propagation = Propagation.REQUIRES_NEW, isolation = Isolation.SERIALIZABLE, rollbackFor = Exception.class)',
        '    public PaymentResult orchestrate(PaymentCommand command, String idemToken) {',
        '        String idemKey = IDEMPOTENCY_PREFIX + Digest.sha256Hex(idemToken + ":" + command.getOrderNo());',
        '        Optional<PaymentResult> cached = idempotencyGateway.read(idemKey, PaymentResult.class);',
        '        if (cached.isPresent()) { return cached.get(); }',
        '        DistributedLock lock = lockManager.tryLock("lock:order:" + command.getOrderNo(), LOCK_TTL);',
        '        if (lock == null) { throw new BusyException("order is processing by another worker"); }',
        '        try {',
        '            validateCommand(command);',
        '            long seq = inMemorySeq.computeIfAbsent(command.getTenantId(), k -> new AtomicLong(0)).incrementAndGet();',
        '            RiskSnapshot risk = riskEngine.evaluate(command, seq);',
        '            if (risk.getScore().compareTo(RISK_THRESHOLD) > 0 && !command.isForceSubmit()) {',
        '                throw new RiskBlockedException("risk score exceeded threshold");',
        '            }',
        '            PaymentAggregate aggregate = aggregateFactory.rehydrateOrCreate(command.getOrderNo(), command.getTenantId());',
        '            aggregate.apply(command.toEvent(clock.instant(), traceContext.currentTraceId()));',
        '            paymentRepository.save(aggregate);',
        '            PaymentResult result = retryTemplate.execute(ctx -> gateway.charge(aggregate.buildChargeRequest(ctx.getRetryCount())));',
        '            outboxPublisher.publish(new PaymentSucceededEvent(aggregate.getOrderNo(), result.getTransactionId(), clock.instant(), traceContext.currentTraceId()));',
        '            idempotencyGateway.write(idemKey, result, Duration.ofHours(6));',
        '            return result;',
        '        } catch (Exception ex) {',
        '            compensator.compensate(command.getOrderNo(), ex.getMessage(), clock.instant());',
        '            throw ex;',
        '        } finally {',
        '            lock.unlock();',
        '        }',
        '    }',
        '}',
      ],
    },
    {
      name: 'rust',
      repeat: 5,
      lines: [
        'use std::collections::{BTreeMap, HashMap};',
        'use std::sync::Arc;',
        'use tokio::sync::{Mutex, RwLock};',
        'use tokio::time::{sleep, Duration, Instant};',
        'use serde::{Deserialize, Serialize};',
        '',
        '#[derive(Debug, Clone, Serialize, Deserialize)]',
        'struct Command { tenant: String, key: String, amount: i64, deadline_ms: u64 }',
        '',
        '#[derive(Debug, Clone, Serialize, Deserialize)]',
        'enum Event { Reserved { id: String, cents: i64 }, Committed { id: String }, Failed { id: String, reason: String } }',
        '',
        '#[derive(Default)]',
        'struct Snapshot {',
        '    ledger: HashMap<String, i64>,',
        '    inbox: BTreeMap<u64, Event>,',
        '    watermark: u64,',
        '}',
        '',
        'struct Orchestrator {',
        '    state: Arc<RwLock<Snapshot>>,',
        '    idempotency: Arc<Mutex<HashMap<String, Event>>>,',
        '}',
        '',
        'impl Orchestrator {',
        '    async fn apply(&self, cmd: Command) -> Result<Event, String> {',
        '        let idem = format!("{}:{}:{}", cmd.tenant, cmd.key, cmd.amount);',
        '        if let Some(hit) = self.idempotency.lock().await.get(&idem).cloned() {',
        '            return Ok(hit);',
        '        }',
        '        let start = Instant::now();',
        '        let mut retries = 0u8;',
        '        loop {',
        '            retries += 1;',
        '            match self.try_once(&cmd).await {',
        '                Ok(evt) => {',
        '                    self.idempotency.lock().await.insert(idem.clone(), evt.clone());',
        '                    return Ok(evt);',
        '                }',
        '                Err(e) if retries < 5 && start.elapsed() < Duration::from_millis(cmd.deadline_ms) => {',
        '                    sleep(Duration::from_millis((retries as u64) * 17)).await;',
        '                    continue;',
        '                }',
        '                Err(e) => return Err(format!("orchestration failed after retries: {}", e)),',
        '            }',
        '        }',
        '    }',
        '',
        '    async fn try_once(&self, cmd: &Command) -> Result<Event, String> {',
        '        let mut guard = self.state.write().await;',
        '        let balance = guard.ledger.get(&cmd.key).copied().unwrap_or_default();',
        '        if balance < cmd.amount {',
        '            let evt = Event::Failed { id: cmd.key.clone(), reason: "insufficient_funds".to_string() };',
        '            guard.watermark += 1;',
        '            guard.inbox.insert(guard.watermark, evt.clone());',
        '            return Ok(evt);',
        '        }',
        '        guard.ledger.insert(cmd.key.clone(), balance - cmd.amount);',
        '        guard.watermark += 1;',
        '        let evt = Event::Committed { id: cmd.key.clone() };',
        '        guard.inbox.insert(guard.watermark, evt.clone());',
        '        Ok(evt)',
        '    }',
        '}',
      ],
    },
    {
      name: 'cpp',
      repeat: 5,
      lines: [
        '#include <algorithm>',
        '#include <atomic>',
        '#include <chrono>',
        '#include <concepts>',
        '#include <future>',
        '#include <map>',
        '#include <mutex>',
        '#include <optional>',
        '#include <queue>',
        '#include <shared_mutex>',
        '#include <string>',
        '#include <unordered_map>',
        '#include <variant>',
        '#include <vector>',
        '',
        'template <typename T>',
        'concept Hashable = requires(T v) { std::hash<T>{}(v); };',
        '',
        'struct Job {',
        '    std::string id;',
        '    std::vector<std::string> deps;',
        '    std::function<std::variant<int, std::string>()> run;',
        '};',
        '',
        'class DagExecutor {',
        'public:',
        '    explicit DagExecutor(std::size_t maxConcurrency) : maxConcurrency_(maxConcurrency) {}',
        '',
        '    void add(Job job) {',
        '        std::unique_lock lk(mu_);',
        '        graph_[job.id] = std::move(job);',
        '    }',
        '',
        '    std::map<std::string, std::variant<int, std::string>> execute() {',
        '        auto order = topoSort();',
        '        std::map<std::string, std::variant<int, std::string>> out;',
        '        std::vector<std::future<void>> inflight;',
        '        std::atomic_size_t cursor{0};',
        '',
        '        auto worker = [&]() {',
        '            while (true) {',
        '                auto idx = cursor.fetch_add(1);',
        '                if (idx >= order.size()) { break; }',
        '                const auto& id = order[idx];',
        '                auto result = graph_.at(id).run();',
        '                {',
        '                    std::unique_lock lk(outMu_);',
        '                    out[id] = std::move(result);',
        '                }',
        '            }',
        '        };',
        '',
        '        for (std::size_t i = 0; i < maxConcurrency_; ++i) {',
        '            inflight.emplace_back(std::async(std::launch::async, worker));',
        '        }',
        '        for (auto& f : inflight) { f.get(); }',
        '        return out;',
        '    }',
        '',
        'private:',
        '    std::vector<std::string> topoSort();',
        '    std::size_t maxConcurrency_;',
        '    std::unordered_map<std::string, Job> graph_;',
        '    std::shared_mutex mu_;',
        '    std::mutex outMu_;',
        '};',
      ],
    },
    {
      name: 'python',
      repeat: 5,
      lines: [
        'import asyncio',
        'import contextvars',
        'import dataclasses',
        'import hashlib',
        'import random',
        'from collections import defaultdict',
        'from typing import Any, Callable, Dict, Iterable, List',
        '',
        'trace_id_var = contextvars.ContextVar("trace_id", default="n/a")',
        '',
        '@dataclasses.dataclass',
        'class TaskSpec:',
        '    key: str',
        '    deps: List[str]',
        '    timeout: float',
        '    fn: Callable[[Dict[str, Any]], "asyncio.Future[Any]"]',
        '',
        '@dataclasses.dataclass',
        'class EngineState:',
        '    done: Dict[str, Any]',
        '    failed: Dict[str, str]',
        '    latency_ms: Dict[str, float]',
        '',
        'class AsyncDagEngine:',
        '    def __init__(self, specs: Iterable[TaskSpec], concurrency: int = 8) -> None:',
        '        self.specs = {s.key: s for s in specs}',
        '        self.children = defaultdict(list)',
        '        self.indegree = defaultdict(int)',
        '        for spec in self.specs.values():',
        '            self.indegree[spec.key] = len(spec.deps)',
        '            for dep in spec.deps:',
        '                self.children[dep].append(spec.key)',
        '        self.sem = asyncio.Semaphore(concurrency)',
        '',
        '    async def run(self, seed: Dict[str, Any]) -> EngineState:',
        '        state = EngineState(done=dict(seed), failed={}, latency_ms={})',
        '        ready = asyncio.Queue()',
        '        for k, v in self.indegree.items():',
        '            if v == 0:',
        '                ready.put_nowait(k)',
        '',
        '        async def worker() -> None:',
        '            while True:',
        '                key = await ready.get()',
        '                if key is None:',
        '                    return',
        '                spec = self.specs[key]',
        '                token = trace_id_var.set(hashlib.sha1(key.encode()).hexdigest()[:12])',
        '                started = asyncio.get_running_loop().time()',
        '                try:',
        '                    async with self.sem:',
        '                        result = await asyncio.wait_for(spec.fn(state.done), timeout=spec.timeout)',
        '                    state.done[key] = result',
        '                except Exception as exc:',
        '                    state.failed[key] = f"{type(exc).__name__}:{exc}"',
        '                finally:',
        '                    elapsed = (asyncio.get_running_loop().time() - started) * 1000',
        '                    state.latency_ms[key] = elapsed',
        '                    trace_id_var.reset(token)',
        '',
        '                for child in self.children[key]:',
        '                    self.indegree[child] -= 1',
        '                    if self.indegree[child] == 0 and child not in state.failed:',
        '                        ready.put_nowait(child)',
        '',
        '        workers = [asyncio.create_task(worker()) for _ in range(6)]',
        '        await ready.join()',
        '        for _ in workers: ready.put_nowait(None)',
        '        await asyncio.gather(*workers)',
        '        return state',
      ],
    },
    {
      name: 'javascript',
      repeat: 5,
      lines: [
        'class CircuitBreaker {',
        '  constructor({ threshold = 0.35, minRequests = 20, coolDownMs = 2500 } = {}) {',
        '    this.threshold = threshold;',
        '    this.minRequests = minRequests;',
        '    this.coolDownMs = coolDownMs;',
        '    this.state = "closed";',
        '    this.window = [];',
        '    this.openUntil = 0;',
        '  }',
        '',
        '  record(ok) {',
        '    this.window.push(ok ? 1 : 0);',
        '    if (this.window.length > 120) this.window.shift();',
        '    const failRatio = 1 - this.window.reduce((a, b) => a + b, 0) / this.window.length;',
        '    if (this.window.length >= this.minRequests && failRatio >= this.threshold) {',
        '      this.state = "open";',
        '      this.openUntil = Date.now() + this.coolDownMs;',
        '    }',
        '  }',
        '',
        '  canPass() {',
        '    if (this.state === "closed") return true;',
        '    if (Date.now() >= this.openUntil) {',
        '      this.state = "half-open";',
        '      return true;',
        '    }',
        '    return false;',
        '  }',
        '}',
        '',
        'export class StreamOrchestrator {',
        '  constructor(fetchers, { concurrency = 6 } = {}) {',
        '    this.fetchers = fetchers;',
        '    this.concurrency = concurrency;',
        '    this.cache = new Map();',
        '    this.breakers = new Map();',
        '  }',
        '',
        '  async *collect(keys, signal) {',
        '    const queue = [...keys];',
        '    const inflight = new Set();',
        '    const next = async () => {',
        '      if (!queue.length) return;',
        '      const key = queue.shift();',
        '      const fn = this.fetchers.get(key);',
        '      if (!fn) return;',
        '      const breaker = this.breakers.get(key) ?? new CircuitBreaker();',
        '      this.breakers.set(key, breaker);',
        '      if (!breaker.canPass()) return;',
        '',
        '      const p = fn({ signal })',
        '        .then((value) => { breaker.record(true); return { key, value, ok: true }; })',
        '        .catch((err) => { breaker.record(false); return { key, error: err, ok: false }; })',
        '        .finally(() => inflight.delete(p));',
        '      inflight.add(p);',
        '    };',
        '',
        '    while (queue.length || inflight.size) {',
        '      while (inflight.size < this.concurrency && queue.length) await next();',
        '      if (!inflight.size) continue;',
        '      const settled = await Promise.race([...inflight]);',
        '      if (settled.ok) this.cache.set(settled.key, settled.value);',
        '      yield settled;',
        '    }',
        '  }',
        '}',
      ],
    },
    {
      name: 'nim',
      repeat: 5,
      lines: [
        'import asyncdispatch, options, tables, sequtils, algorithm, strformat, times, hashes, locks',
        '',
        'type',
        '  EventKind = enum',
        '    ekWrite, ekCompact, ekSnapshot, ekReplicate',
        '',
        '  Event = object',
        '    tenantId: string',
        '    streamId: string',
        '    offset: int64',
        '    payload: string',
        '    kind: EventKind',
        '    ts: DateTime',
        '',
        '  ShardState = ref object',
        '    watermark: int64',
        '    pending: Table[int64, Future[void]]',
        '    snapshotEvery: int',
        '    lock: Lock',
        '',
        'proc routeShard(tenantId, streamId: string; shardCount: int): int =',
        '  result = abs(hash(tenantId & ":" & streamId)) mod shardCount',
        '',
        'proc applyEvent(state: ShardState; ev: Event): Future[void] {.async.} =',
        '  acquire(state.lock)',
        '  defer: release(state.lock)',
        '  if ev.offset <= state.watermark:',
        '    return',
        '',
        '  let delayMs = (ev.payload.len mod 13) + 3',
        '  await sleepAsync(delayMs)',
        '  state.watermark = ev.offset',
        '',
        '  if state.watermark mod int64(state.snapshotEvery) == 0:',
        '    let sid = state.watermark',
        '    state.pending[sid] = asyncCheck proc() {.async.} =',
        '      await sleepAsync(20)',
        '      echo &"[snapshot] stream={ev.streamId} offset={sid}"',
        '',
        'proc mergeOrdered(buffers: seq[seq[Event]]): seq[Event] =',
        '  result = @[]',
        '  for b in buffers: result.add(b)',
        '  result.sort(proc(a, b: Event): int = cmp(a.offset, b.offset))',
        '',
        'proc replay(shards: seq[ShardState]; buffers: seq[seq[Event]]) {.async.} =',
        '  for ev in mergeOrdered(buffers):',
        '    let idx = routeShard(ev.tenantId, ev.streamId, shards.len)',
        '    await shards[idx].applyEvent(ev)',
      ],
    },
  ];

  const snippetCatalog = rawSnippetCatalog.map((entry) => ({
    ...entry,
    lines: tokenizeSnippet(entry.lines, entry.name),
  }));

  const selectedSnippet = snippetCatalog[Math.floor(Math.random() * snippetCatalog.length)];
  const snippetLines = selectedSnippet.lines;
  const repeatCount = selectedSnippet.repeat ?? 6;

  const codeLines = [];
  for (let i = 0; i < repeatCount; i += 1) {
    snippetLines.forEach((line) => codeLines.push(line));
    codeLines.push([['', 'plain']]);
  }

  const themes = {
    light: {
      background: '#f7f9fd',
      editor: '#fbfcff',
      gutter: '#f1f4fa',
      divider: '#d5dde9',
      guide: 'rgba(115, 138, 170, 0.14)',
      currentLine: 'rgba(46, 118, 245, 0.13)',
      lineNo: '#8a93a4',
      lineNoActive: '#4f5e77',
      caret: '#2e76f5',
      markerOk: '#238551',
      markerWarn: '#bb7a15',
      fadeTop: 'rgba(0, 0, 0, 0)',
      fadeBottom: 'rgba(0, 0, 0, 0)',
      code: {
        plain: '#3e4553',
        keyword: '#0f59c5',
        type: '#136a4d',
        method: '#7c3ec1',
        annotation: '#9a5f0a',
        comment: '#758191',
        string: '#1c7f3f',
        number: '#8f437d',
        field: '#4c607f',
        operator: '#4a607e',
        constant: '#3f67b0',
      },
    },
    dark: {
      background: '#1d1f23',
      editor: '#1f2126',
      gutter: '#1a1b1f',
      divider: '#333842',
      guide: 'rgba(103, 122, 157, 0.2)',
      currentLine: 'rgba(76, 149, 255, 0.17)',
      lineNo: '#5f6674',
      lineNoActive: '#8da1bf',
      caret: '#4c95ff',
      markerOk: '#53d779',
      markerWarn: '#e8b04a',
      fadeTop: 'rgba(24, 26, 32, 0.5)',
      fadeBottom: 'rgba(20, 22, 28, 0.38)',
      code: {
        plain: '#ccd3dd',
        keyword: '#cc7832',
        type: '#4ea1f2',
        method: '#d8c66c',
        annotation: '#bbb529',
        comment: '#6a7484',
        string: '#6aab73',
        number: '#8a6db6',
        field: '#c4cbd6',
        operator: '#909ba9',
        constant: '#9876aa',
      },
    },
  };

  const clamp = (value, min, max) => Math.max(min, Math.min(max, value));
  const getTheme = () => (document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light');
  const tokenCount = (tokens) => tokens.reduce((sum, token) => sum + token[0].length, 0);

  const resizeCanvas = () => {
    const rect = hero.getBoundingClientRect();
    width = Math.max(1, Math.round(rect.width));
    height = Math.max(1, Math.round(rect.height));
    dpr = window.devicePixelRatio || 1;
    canvas.width = Math.floor(width * dpr);
    canvas.height = Math.floor(height * dpr);
    canvas.style.width = `${width}px`;
    canvas.style.height = `${height}px`;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  };

  const drawTokenLine = (tokens, x, y, charBudget, palette) => {
    let cursorX = x;
    const limit = Number.isFinite(charBudget);
    let remaining = limit ? charBudget : 0;

    for (const token of tokens) {
      if (limit && remaining <= 0) break;

      const text = token[0];
      if (!text) continue;

      const visibleText = limit ? text.slice(0, remaining) : text;
      ctx.fillStyle = palette.code[token[1]] || palette.code.plain;
      ctx.fillText(visibleText, cursorX, y);
      cursorX += ctx.measureText(visibleText).width;

      if (limit) {
        remaining -= visibleText.length;
      }
    }

    return cursorX - x;
  };

  const drawCodeEditor = (palette, elapsed, isStatic) => {
    const gutterWidth = clamp(Math.round(width * 0.08), 56, 92);
    const fontSize = clamp(Math.round(width / 98), 14, 20);
    const lineHeight = Math.round(fontSize * 1.72);
    const topPadding = Math.round(lineHeight * 1.24);
    const codeStartX = gutterWidth + 18;
    const visibleLines = Math.max(1, Math.ceil((height - topPadding * 2) / lineHeight) + 2);
    const baseLineNo = 1;
    const scrollLeadLines = 2;

    const lineCharTotals = codeLines.map((line) => tokenCount(line));
    const lineDurations = lineCharTotals.map((chars) => Math.max(150, chars * 8));
    const totalTypingDuration = lineDurations.reduce((sum, duration) => sum + duration, 0);
    const holdDuration = 500;
    const cycleDuration = totalTypingDuration + holdDuration;

    ctx.fillStyle = palette.background;
    ctx.fillRect(0, 0, width, height);
    ctx.fillStyle = palette.editor;
    ctx.fillRect(gutterWidth, 0, width - gutterWidth, height);
    ctx.fillStyle = palette.gutter;
    ctx.fillRect(0, 0, gutterWidth, height);
    ctx.fillStyle = palette.divider;
    ctx.fillRect(gutterWidth, 0, 1, height);

    ctx.strokeStyle = palette.guide;
    ctx.lineWidth = 1;
    for (let x = codeStartX + 110; x < width; x += 120) {
      ctx.beginPath();
      ctx.moveTo(x, 0);
      ctx.lineTo(x, height);
      ctx.stroke();
    }

    const cyclePos = isStatic ? totalTypingDuration + holdDuration : elapsed % cycleDuration;
    const writingPhase = cyclePos < totalTypingDuration;

    let completedLines = codeLines.length;
    let activeLine = codeLines.length - 1;
    let activeLineProgress = 1;

    if (writingPhase && !isStatic) {
      let remaining = cyclePos;
      completedLines = 0;
      activeLine = 0;
      activeLineProgress = 0;

      for (let i = 0; i < lineDurations.length; i += 1) {
        const duration = lineDurations[i];
        if (remaining >= duration) {
          remaining -= duration;
          completedLines += 1;
          continue;
        }
        activeLine = i;
        activeLineProgress = duration > 0 ? remaining / duration : 1;
        break;
      }
    }

    const startLine = isStatic
      ? Math.max(0, codeLines.length - visibleLines)
      : Math.max(0, completedLines - Math.max(1, visibleLines - 1 - scrollLeadLines));

    ctx.font = `500 ${fontSize}px "JetBrains Mono", "Fira Code", "SFMono-Regular", Consolas, monospace`;
    ctx.textBaseline = 'alphabetic';

    let caret = null;
    for (let i = 0; i < visibleLines; i += 1) {
      const lineIndex = startLine + i;
      const tokens = codeLines[lineIndex];
      if (!tokens) break;

      const y = topPadding + i * lineHeight;
      if (y < -lineHeight || y > height + lineHeight) continue;

      const charsInLine = lineCharTotals[lineIndex];
      const lineCompleted = isStatic || lineIndex < completedLines || !writingPhase;
      const isActive = writingPhase && lineIndex === activeLine;

      let charBudget = 0;
      if (lineCompleted) {
        charBudget = Infinity;
      } else if (isActive) {
        charBudget = charsInLine > 0 ? Math.floor(charsInLine * clamp(activeLineProgress, 0, 1)) : 0;
        if (charsInLine > 0) {
          charBudget = Math.max(1, charBudget);
        }
      }

      const hasStarted = lineCompleted || isActive;

      if (isActive) {
        ctx.fillStyle = palette.currentLine;
        ctx.fillRect(gutterWidth + 1, y - lineHeight * 0.76, width - gutterWidth - 1, lineHeight);
      }

      if (hasStarted) {
        const lineNumber = String(baseLineNo + lineIndex);
        ctx.fillStyle = isActive ? palette.lineNoActive : palette.lineNo;
        ctx.fillText(lineNumber, gutterWidth - 10 - ctx.measureText(lineNumber).width, y);
      }

      let drawnWidth = 0;
      if (hasStarted && (charBudget > 0 || charBudget === Infinity)) {
        drawnWidth = drawTokenLine(tokens, codeStartX, y, charBudget, palette);
      }

      if (isActive && writingPhase && Math.floor(elapsed / 430) % 2 === 0) {
        caret = { x: codeStartX + drawnWidth + 1, y };
      }

      if (!hasStarted) continue;

      if ((lineIndex + 7) % 19 === 0) {
        ctx.beginPath();
        ctx.fillStyle = palette.markerOk;
        ctx.arc(12, y - lineHeight * 0.38, 3.2, 0, Math.PI * 2);
        ctx.fill();
      } else if ((lineIndex + 3) % 23 === 0) {
        ctx.beginPath();
        ctx.fillStyle = palette.markerWarn;
        ctx.arc(12, y - lineHeight * 0.38, 3.2, 0, Math.PI * 2);
        ctx.fill();
      }
    }

    if (caret) {
      ctx.fillStyle = palette.caret;
      ctx.fillRect(caret.x, caret.y - fontSize, 1.8, Math.max(12, fontSize + 1));
    }
  };

  const render = (timestamp) => {
    const elapsed = timestamp - startAt;
    const palette = themes[getTheme()];
    ctx.clearRect(0, 0, width, height);
    drawCodeEditor(palette, elapsed, false);
    rafId = requestAnimationFrame(render);
  };

  const drawStatic = () => {
    const palette = themes[getTheme()];
    ctx.clearRect(0, 0, width, height);
    drawCodeEditor(palette, 0, true);
  };

  const stop = () => {
    if (!rafId) return;
    cancelAnimationFrame(rafId);
    rafId = null;
  };

  const start = () => {
    stop();
    resizeCanvas();
    startAt = performance.now();
    if (reducedMotionQuery.matches) {
      drawStatic();
      return;
    }
    rafId = requestAnimationFrame(render);
  };

  const onResize = () => {
    resizeCanvas();
    if (reducedMotionQuery.matches) {
      drawStatic();
    }
  };

  const themeObserver = new MutationObserver(() => {
    if (reducedMotionQuery.matches) {
      drawStatic();
    }
  });

  themeObserver.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['data-theme'],
  });

  if (typeof ResizeObserver !== 'undefined') {
    const resizeObserver = new ResizeObserver(onResize);
    resizeObserver.observe(hero);
  }

  if (reducedMotionQuery.addEventListener) {
    reducedMotionQuery.addEventListener('change', start);
  } else if (reducedMotionQuery.addListener) {
    reducedMotionQuery.addListener(start);
  }

  window.addEventListener('resize', onResize);
  window.addEventListener('load', onResize);

  start();
})();
