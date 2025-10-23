import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import assets from './assets'
import cache from './cache'
import queries from './queries'
/**
* @see \Barryvdh\Debugbar\Controllers\OpenHandlerController::openhandler
 * @see vendor/barryvdh/laravel-debugbar/src/Controllers/OpenHandlerController.php:43
 * @route '/_debugbar/open'
 */
export const openhandler = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: openhandler.url(options),
    method: 'get',
})

openhandler.definition = {
    methods: ["get","head"],
    url: '/_debugbar/open',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Barryvdh\Debugbar\Controllers\OpenHandlerController::openhandler
 * @see vendor/barryvdh/laravel-debugbar/src/Controllers/OpenHandlerController.php:43
 * @route '/_debugbar/open'
 */
openhandler.url = (options?: RouteQueryOptions) => {
    return openhandler.definition.url + queryParams(options)
}

/**
* @see \Barryvdh\Debugbar\Controllers\OpenHandlerController::openhandler
 * @see vendor/barryvdh/laravel-debugbar/src/Controllers/OpenHandlerController.php:43
 * @route '/_debugbar/open'
 */
openhandler.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: openhandler.url(options),
    method: 'get',
})
/**
* @see \Barryvdh\Debugbar\Controllers\OpenHandlerController::openhandler
 * @see vendor/barryvdh/laravel-debugbar/src/Controllers/OpenHandlerController.php:43
 * @route '/_debugbar/open'
 */
openhandler.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: openhandler.url(options),
    method: 'head',
})

    /**
* @see \Barryvdh\Debugbar\Controllers\OpenHandlerController::openhandler
 * @see vendor/barryvdh/laravel-debugbar/src/Controllers/OpenHandlerController.php:43
 * @route '/_debugbar/open'
 */
    const openhandlerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: openhandler.url(options),
        method: 'get',
    })

            /**
* @see \Barryvdh\Debugbar\Controllers\OpenHandlerController::openhandler
 * @see vendor/barryvdh/laravel-debugbar/src/Controllers/OpenHandlerController.php:43
 * @route '/_debugbar/open'
 */
        openhandlerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: openhandler.url(options),
            method: 'get',
        })
            /**
* @see \Barryvdh\Debugbar\Controllers\OpenHandlerController::openhandler
 * @see vendor/barryvdh/laravel-debugbar/src/Controllers/OpenHandlerController.php:43
 * @route '/_debugbar/open'
 */
        openhandlerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: openhandler.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    openhandler.form = openhandlerForm
/**
* @see \Barryvdh\Debugbar\Controllers\OpenHandlerController::clockwork
 * @see vendor/barryvdh/laravel-debugbar/src/Controllers/OpenHandlerController.php:77
 * @route '/_debugbar/clockwork/{id}'
 */
export const clockwork = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: clockwork.url(args, options),
    method: 'get',
})

clockwork.definition = {
    methods: ["get","head"],
    url: '/_debugbar/clockwork/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Barryvdh\Debugbar\Controllers\OpenHandlerController::clockwork
 * @see vendor/barryvdh/laravel-debugbar/src/Controllers/OpenHandlerController.php:77
 * @route '/_debugbar/clockwork/{id}'
 */
clockwork.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    id: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        id: args.id,
                }

    return clockwork.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Barryvdh\Debugbar\Controllers\OpenHandlerController::clockwork
 * @see vendor/barryvdh/laravel-debugbar/src/Controllers/OpenHandlerController.php:77
 * @route '/_debugbar/clockwork/{id}'
 */
clockwork.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: clockwork.url(args, options),
    method: 'get',
})
/**
* @see \Barryvdh\Debugbar\Controllers\OpenHandlerController::clockwork
 * @see vendor/barryvdh/laravel-debugbar/src/Controllers/OpenHandlerController.php:77
 * @route '/_debugbar/clockwork/{id}'
 */
clockwork.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: clockwork.url(args, options),
    method: 'head',
})

    /**
* @see \Barryvdh\Debugbar\Controllers\OpenHandlerController::clockwork
 * @see vendor/barryvdh/laravel-debugbar/src/Controllers/OpenHandlerController.php:77
 * @route '/_debugbar/clockwork/{id}'
 */
    const clockworkForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: clockwork.url(args, options),
        method: 'get',
    })

            /**
* @see \Barryvdh\Debugbar\Controllers\OpenHandlerController::clockwork
 * @see vendor/barryvdh/laravel-debugbar/src/Controllers/OpenHandlerController.php:77
 * @route '/_debugbar/clockwork/{id}'
 */
        clockworkForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: clockwork.url(args, options),
            method: 'get',
        })
            /**
* @see \Barryvdh\Debugbar\Controllers\OpenHandlerController::clockwork
 * @see vendor/barryvdh/laravel-debugbar/src/Controllers/OpenHandlerController.php:77
 * @route '/_debugbar/clockwork/{id}'
 */
        clockworkForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: clockwork.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    clockwork.form = clockworkForm
const debugbar = {
    openhandler: Object.assign(openhandler, openhandler),
clockwork: Object.assign(clockwork, clockwork),
assets: Object.assign(assets, assets),
cache: Object.assign(cache, cache),
queries: Object.assign(queries, queries),
}

export default debugbar