import TaskController from './TaskController'
import ProjectController from './ProjectController'
const front = {
    TaskController: Object.assign(TaskController, TaskController),
ProjectController: Object.assign(ProjectController, ProjectController),
}

export default front