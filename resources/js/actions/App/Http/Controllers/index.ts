import front from './front'
import Settings from './Settings'
import Auth from './Auth'
const Controllers = {
    front: Object.assign(front, front),
Settings: Object.assign(Settings, Settings),
Auth: Object.assign(Auth, Auth),
}

export default Controllers