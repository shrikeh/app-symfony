<?php

declare(strict_types=1);

namespace Tests\GrumPHP\Tasks;

use GrumPHP\Formatter\ProcessFormatterInterface;
use GrumPHP\Process\ProcessBuilder;
use GrumPHP\Runner\TaskResult;
use GrumPHP\Runner\TaskResultInterface;
use GrumPHP\Task\Config\ConfigOptionsResolver;
use GrumPHP\Task\Config\EmptyTaskConfig;
use GrumPHP\Task\Config\TaskConfigInterface;
use GrumPHP\Task\Context\ContextInterface;
use GrumPHP\Task\Context\GitPreCommitContext;
use GrumPHP\Task\Context\RunContext;
use GrumPHP\Task\TaskInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function count;

final readonly class PhpBenchTask implements TaskInterface
{
    public static function getConfigurableOptions(): ConfigOptionsResolver
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'resources' => null,
            'format' => null,
            'suite' => null,
            'profile' => null,
            'stop_on_failure' => false,
        ]);

        return ConfigOptionsResolver::fromClosure(
            static fn (array $options): array => $resolver->resolve($options)
        );
    }

    public function __construct(
        private ProcessBuilder $processBuilder,
        private ProcessFormatterInterface $formatter,
        private TaskConfigInterface $config = new EmptyTaskConfig(),
    ) {
    }

    public function canRunInContext(ContextInterface $context): bool
    {
        return $context instanceof GitPreCommitContext || $context instanceof RunContext;
    }

    public function run(ContextInterface $context): TaskResultInterface
    {
        $files = $context->getFiles()->name('*.php');
        if (0 === count($files)) {
            return TaskResult::createSkipped($this, $context);
        }

        $arguments = $this->processBuilder->createArgumentsForCommand('phpbench');

        $process = $this->processBuilder->buildProcess($arguments);
        $process->run();
        if (!$process->isSuccessful()) {
            return TaskResult::createFailed($this, $context, $this->formatter->format($process));
        }

        return TaskResult::createPassed($this, $context);
    }

    public function getConfig(): TaskConfigInterface
    {
        return $this->config;
    }

    public function withConfig(TaskConfigInterface $config): TaskInterface
    {
        return new self(
            $this->processBuilder,
            $this->formatter,
            $config
        );
    }
}
