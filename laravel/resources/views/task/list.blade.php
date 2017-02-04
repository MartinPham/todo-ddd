<ul>
    @foreach($remaining_tasks as $task)
    <li>
        {{ $task->getId() }} - {{ $task->getName() }} ({{ $task->getStatus() }})
    </li>
    @endforeach

    @foreach($completed_tasks as $task)
    <li>
        {{ $task->getId() }} - {{ $task->getName() }} ({{ $task->getStatus() }})
    </li>
    @endforeach
</ul>

