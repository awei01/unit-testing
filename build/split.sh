git subsplit init git@github.com:awei01/unit-testing.git
git subsplit publish --heads="master" ClassSpy:git@github.com:unit-testing/class-spy.git
git subsplit publish --heads="master" FunctionSpy:git@github.com:unit-testing/function-spy.git
git subsplit publish --heads="master" MockeryHelper:git@github.com:unit-testing/mockery-helper.git
rm -r -f .subsplit/
