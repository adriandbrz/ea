{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.master')

@section('title')
    @lang('server.config.startup.header')
@endsection

@section('content-header')
    <h1>@lang('server.config.startup.header')<small>@lang('server.config.startup.header_sub')</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('index') }}">@lang('strings.home')</a></li>
        <li><a href="{{ route('server.index', $server->uuidShort) }}">{{ $server->name }}</a></li>
        <li>@lang('navigation.server.configuration')</li>
        <li class="active">@lang('navigation.server.startup_parameters')</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('server.config.startup.command')</h3>
            </div>
            <div class="box-body">
                <div class="form-group no-margin-bottom">
                    <input type="text" class="form-control" readonly value="{{ $startup }}" />
                </div>
            </div>
        </div>
    </div>
    @can('edit-startup', $server)
        <form action="{{ route('server.settings.startup', $server->uuidShort) }}" method="POST">
            @foreach($variables as $v)
                <div class="col-xs-12 col-md-4 col-sm-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ $v->name }}</h3>
                        </div>
                        <div class="box-body">
                            <input
                                @if($v->user_editable)
                                    name="environment[{{ $v->env_variable }}]"
                                @else
                                    readonly
                                @endif
                            class="form-control" type="text" value="{{ old('environment.' . $v->env_variable, $server_values[$v->env_variable]) }}" />
                            <p class="small text-muted">{{ $v->description }}</p>
                            <p class="no-margin">
                                @if($v->required && $v->user_editable )
                                    <span class="label label-danger">@lang('strings.required')</span>
                                @elseif(! $v->required && $v->user_editable)
                                    <span class="label label-default">@lang('strings.optional')</span>
                                @endif
                                @if(! $v->user_editable)
                                    <span class="label label-warning">@lang('strings.read_only')</span>
                                @endif
                            </p>
                        </div>
                        <div class="box-footer">
                            <p class="no-margin text-muted small"><strong>@lang('server.config.startup.startup_regex'):</strong> <code>{{ $v->rules }}</code></p>
                        </div>
                    </div>
                </div>
                <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Service Configuration</h3>
                </div>
                <div class="box-body row">
                    <div class="col-xs-12">
                        <p class="small text-danger">
                            Changing any of the below values will result in the server processing a re-install command. The server will be stopped and will then proceed.
                            If you are changing the pack, existing data <em>may</em> be overwritten. If you would like the service scripts to not run, ensure the box is checked at the bottom.
                        </p>
                        <p class="small text-danger">
                            <strong>This is a destructive operation in many cases. This server will be stopped immediately in order for this action to proceed.</strong>
                        </p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="pNestId">Nest</label>
                        <select name="nest_id" id="pNestId" class="form-control">
                            @foreach($nests as $nest)
                                <option value="{{ $nest->id }}"
                                    @if($nest->id === $server->nest_id)
                                        selected
                                    @endif
                                >{{ $nest->name }}</option>
                            @endforeach
                        </select>
                        <p class="small text-muted no-margin">Select the Nest that this server will be grouped into.</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="pEggId">Egg</label>
                        <select name="egg_id" id="pEggId" class="form-control"></select>
                        <p class="small text-muted no-margin">Select the Egg that will provide processing data for this server.</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="pPackId">Data Pack</label>
                        <select name="pack_id" id="pPackId" class="form-control"></select>
                        <p class="small text-muted no-margin">Select a data pack to be automatically installed on this server when first created.</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="checkbox checkbox-primary no-margin-bottom">
                            <input id="pSkipScripting" name="skip_scripts" type="checkbox" value="1" @if($server->skip_scripts) checked @endif />
                            <label for="pSkipScripting" class="strong">Skip Egg Install Script</label>
                        </div>
           @endforeach
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-footer">
                        {!! csrf_field() !!}
                        {!! method_field('PATCH') !!}
                        <input type="submit" class="btn btn-primary btn-sm pull-right" value="@lang('server.config.startup.update')" />
                    </div>
                </div>
            </div>
        </form>
    @endcan
</div>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('js/frontend/server.socket.js') !!}
@endsection
