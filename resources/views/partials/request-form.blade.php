@php $selected = old('department_id', $selectedDepartment ?? request('department')); @endphp
<form method="POST" action="{{ route('request.store') }}" class="card" id="form">
  @csrf
  <h3 style="margin-bottom:18px">{{ __('site.form.title') }}</h3>
  @if ($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif
  <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off">
  <div class="form-grid">
    <div class="field"><label>{{ __('site.form.name') }} *</label><input name="name" required maxlength="120" value="{{ old('name', auth()->user()?->name) }}"></div>
    <div class="field"><label>{{ __('site.form.phone') }} *</label><input name="phone" type="tel" required maxlength="40" value="{{ old('phone', auth()->user()?->phone) }}" placeholder="+374 ..."></div>
    <div class="field"><label>{{ __('site.form.email') }}</label><input name="email" type="email" maxlength="150" value="{{ old('email', auth()->user()?->email) }}"></div>
    <div class="field"><label>{{ __('site.form.client_type') }}</label>
      <select name="client_type">
        <option value="">—</option>
        @foreach(__('site.form.types') as $k => $v)<option value="{{ $k }}" @selected(old('client_type') === $k)>{{ $v }}</option>@endforeach
      </select>
    </div>
    <div class="field full"><label>{{ __('site.form.department') }}</label>
      <select name="department_id">
        <option value="">{{ __('site.form.choose') }}</option>
        @foreach($departments as $d)<option value="{{ $d->id }}" @selected((string) $selected === (string) $d->id)>{{ $d->t('name') }}</option>@endforeach
      </select>
    </div>
    <div class="field full"><label>{{ __('site.form.message') }} *</label><textarea name="message" required maxlength="5000">{{ old('message') }}</textarea></div>
    <label class="check full"><input type="checkbox" name="agree" value="1" required> {{ __('site.form.agree') }}</label>
    <div class="full" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
      <span class="note"><x-icon name="lock" size="14"/> {{ __('site.form.warning') }}</span>
      <button class="btn btn-primary">{{ __('site.form.submit') }} <x-icon name="arrow" size="18"/></button>
    </div>
    @guest
      @if(setting('registration_enabled') === '1')
        <div class="full note"><x-icon name="user" size="14"/> <a href="{{ route('register') }}">{{ __('site.form.registered_hint') }}</a></div>
      @endif
    @endguest
  </div>
</form>
