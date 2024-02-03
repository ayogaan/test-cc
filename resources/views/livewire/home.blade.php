<div style="">
    
    <div>
        <form wire:submit.prevent="store">
        <input type="text" wire:model="apikey" placeholder="apikey"/>
        <input type="number" wire:model="amount" placeholder="withdraw"/>
        <input type="submit" value="submit">
        </form>
    </div>
    
    <div>
        <form wire:submit.prevent="withdrawl">
        <input type="number" wire:model="wd" placeholder="deposit"/>
        <input type="submit" value="submit">
        </form>
    </div>
</div>