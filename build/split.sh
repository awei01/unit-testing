git subsplit init git@github.com:awei01/unit-testing.git
git subsplit publish --heads="master" --no-tags FunctionSpy:git@github.com:unit-testing/function-spy.git
git subsplit publish --heads="master" --no-tags MockeryHelper:git@github.com:unit-testing/mockery-helper.git
rm -r -f .subsplit/